<?php

namespace App\Http\Controllers;

use App\Models\SuivrePointage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SuivrePointageController extends Controller
{
    /**
     * Define the target UITS location coordinates and a radius for proximity check.
     * VOUS DEVEZ DÉFINIR LES BONNES LATITUDE ET LONGITUDE POUR "N° 68 Rue Camille St Saëns, Casablanca 20300, Maroc".
     * Utilisez un outil comme Google Maps pour obtenir des coordonnées précises.
     * Exemple : 33.5855, -7.6329 (Ce sont des approximations, obtenez les précises !)
     */
    private const UITS_LATITUDE = 33.5855; // <-- CHANGEZ CECI PAR LA VRAIE LATITUDE DE VOTRE BUREAU
    private const UITS_LONGITUDE = -7.6329; // <-- CHANGEZ CECI PAR LA VRAIE LONGITUDE DE VOTRE BUREAU
    private const PROXIMITY_RADIUS_METERS = 300; // Définir la distance (en mètres) à laquelle l'utilisateur est considéré "chez UITS"

    /**
     * Constructeur du contrôleur avec gestion des permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:pointage-list', ['only' => ['index', 'show']]);
        // Aucune permission spécifique pour 'pointer' ici, car la restriction par rôle est faite dans la méthode elle-même.
    }

    /**
     * Afficher la liste des pointages.
     */
    public function index()
    {
        $utilisateur = auth()->user();

        $pointageEnCours = null;
        if (!($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1'))) {
            $pointageEnCours = SuivrePointage::where('iduser', Auth::id())
                ->whereDate('date_pointage', Carbon::today('Africa/Casablanca'))
                ->whereNull('heure_depart')
                ->first();
        }

        $requete = SuivrePointage::with('user');

        if ($recherche = request('search')) {
            $requete->whereHas('user', function ($query) use ($recherche) {
                $query->where('name', 'like', "%{$recherche}%");
            })
            ->orWhereDate('date_pointage', 'like', "%{$recherche}%");
        }

        if ($dateDebut = request('date_debut')) {
            $requete->whereDate('date_pointage', '>=', $dateDebut);
        }

        if ($statut = request('statut')) {
            if ($statut === 'en_cours') {
                $requete->whereNull('heure_depart');
            } elseif ($statut === 'termine') {
                $requete->whereNotNull('heure_depart');
            }
        }

        $requete->orderBy('date_pointage', 'DESC')->orderBy('heure_arrivee', 'DESC');

        if ($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1')) {
            $pointages = $requete->paginate(10);
        } else {
            $pointages = $requete->where('iduser', $utilisateur->id)->paginate(10);
        }

        return view('suivre_pointage.index', compact('pointages', 'pointageEnCours'));
    }

    /**
     * Effectuer un pointage (arrivée ou départ).
     */
    public function pointer(Request $request)
    {
        $utilisateur = auth()->user();

        if ($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1')) {
            return redirect()->back()->with('error', 'En tant qu\'administrateur, vous n\'êtes pas autorisé à pointer.');
        }

        // user_latitude et user_longitude sont maintenant "nullable" (peuvent être nuls)
        $donneesValidees = $request->validate([
            'description' => 'nullable|string|max:500',
            'user_latitude' => 'nullable|numeric|between:-90,90',
            'user_longitude' => 'nullable|numeric|between:-180,180',
            'localisation' => 'nullable|string|max:255', // Acceptation de la localisation du frontend comme information/fallback
        ]);

        try {
            DB::beginTransaction();

            $casablancaNow = Carbon::now('Africa/Casablanca');
            $casablancaToday = Carbon::today('Africa/Casablanca');

            $userLatitude = $donneesValidees['user_latitude'];
            $userLongitude = $donneesValidees['user_longitude'];
            $determinedLocation = 'Non spécifiée'; // Valeur par défaut si la géolocalisation échoue

            // Tenter le calcul de distance UNIQUEMENT si les coordonnées sont fournies
            if ($userLatitude !== null && $userLongitude !== null) {
                $distance = $this->haversineGreatCircleDistance(
                    self::UITS_LATITUDE, self::UITS_LONGITUDE,
                    $userLatitude, $userLongitude
                );
                $determinedLocation = ($distance <= self::PROXIMITY_RADIUS_METERS) ? 'UITS' : 'Non-UITS'; // Correction du 'mach UITS' en 'Non-UITS' pour plus de clarté en français
            } else {
                // Si les coordonnées sont nulles, utiliser la valeur 'localisation' envoyée du frontend (qui sera un message de statut)
                $determinedLocation = $donneesValidees['localisation'] ?? 'Localisation échouée (Non spécifiée)';
            }

            $pointageEnCours = SuivrePointage::where('iduser', Auth::id())
                ->whereDate('date_pointage', $casablancaToday)
                ->whereNull('heure_depart')
                ->first();

            if ($pointageEnCours) {
                // Pointage de départ : l'utilisateur est déjà arrivé et n'est pas encore parti.
                $pointageEnCours->update([
                    'heure_depart' => $casablancaNow, // Enregistre l'heure de départ actuelle de Casablanca
                    'description' => $donneesValidees['description'] ?? $pointageEnCours->description,
                    'localisation' => $determinedLocation, // Met à jour la localisation au départ également
                    'user_latitude' => $userLatitude, // Enregistre les coordonnées au départ
                    'user_longitude' => $userLongitude, // Enregistre les coordonnées au départ
                ]);

                $message = 'Pointage de départ enregistré avec succès.';
                Log::info('Pointage de départ', ['user_id' => Auth::id(), 'pointage_id' => $pointageEnCours->id, 'localisation' => $determinedLocation, 'latitude' => $userLatitude, 'longitude' => $userLongitude]);
            } else {
                // Pointage d'arrivée : l'utilisateur n'a pas encore pointé son arrivée aujourd'hui.
                // Vérifier s'il a déjà pointé son arrivée ET son départ aujourd'hui
                $alreadyClockedOutToday = SuivrePointage::where('iduser', Auth::id())
                    ->whereDate('date_pointage', $casablancaToday)
                    ->whereNotNull('heure_depart')
                    ->exists();

                if ($alreadyClockedOutToday) {
                    DB::rollBack(); // Pas besoin de créer un pointage
                    return redirect()->back()->with('info', 'Vous avez déjà pointé votre arrivée et votre départ pour aujourd\'hui.');
                }

                $nouveauPointage = SuivrePointage::create([
                    'iduser' => Auth::id(),
                    'heure_arrivee' => $casablancaNow, // Enregistre l'heure d'arrivée actuelle de Casablanca
                    'date_pointage' => $casablancaToday, // La date du pointage sera celle de Casablanca
                    'description' => $donneesValidees['description'] ?? null,
                    'localisation' => $determinedLocation, // Définit la localisation déterminée (ou le statut de fallback)
                    'user_latitude' => $userLatitude, // Enregistre les coordonnées à l'arrivée
                    'user_longitude' => $userLongitude, // Enregistre les coordonnées à l'arrivée
                ]);

                $message = 'Pointage d\'arrivée enregistré avec succès.';
                Log::info('Pointage d\'arrivée', ['user_id' => Auth::id(), 'pointage_id' => $nouveauPointage->id, 'localisation' => $determinedLocation, 'latitude' => $userLatitude, 'longitude' => $userLongitude]);
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Erreur de validation lors du pointage', [
                'user_id' => Auth::id(),
                'validation_errors' => $e->errors(),
                'request_data' => $request->all(),
                'message' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Erreur de validation lors du pointage : ' . json_encode($e->errors()));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du pointage', ['user_id' => Auth::id(), 'error_message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du pointage. Veuillez réessayer. Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Calcule la distance grand cercle entre deux points sur une sphère.
     */
    private function haversineGreatCircleDistance(
        float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo, float $earthRadius = 6371000
    ): float {
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    /**
     * Afficher les détails d'un pointage.
     */
    public function show($id)
    {
        $pointage = SuivrePointage::with('user')->findOrFail($id);

        $utilisateur = auth()->user();
        if (!($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1')) && $pointage->iduser !== $utilisateur->id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('suivre_pointage.show', compact('pointage'));
    }

    /**
     * Obtenir les statistiques de pointage pour un utilisateur.
     */
    public function statistiques(Request $request)
    {
        $utilisateur = auth()->user();
        $moisActuel = $request->get('mois', Carbon::now('Africa/Casablanca')->format('Y-m'));

        $requete = SuivrePointage::where('iduser', $utilisateur->id)
            ->whereYear('date_pointage', '=', Carbon::parse($moisActuel, 'Africa/Casablanca')->year)
            ->whereMonth('date_pointage', '=', Carbon::parse($moisActuel, 'Africa/Casablanca')->month);

        $statistiques = [
            'total_pointages' => $requete->count(),
            'pointages_complets' => $requete->clone()->whereNotNull('heure_depart')->count(),
            'pointages_en_cours' => $requete->clone()->whereNull('heure_depart')->count(),
            'temps_total_travaille' => $this->calculerTempsTotalTravaille($utilisateur->id, $moisActuel),
        ];

        return response()->json($statistiques);
    }

    /**
     * Calculer le temps total travaillé pour un utilisateur.
     */
    private function calculerTempsTotalTravaille($utilisateurId, $mois)
    {
        $pointages = SuivrePointage::where('iduser', $utilisateurId)
            ->whereYear('date_pointage', '=', Carbon::parse($mois, 'Africa/Casablanca')->year)
            ->whereMonth('date_pointage', '=', Carbon::parse($mois, 'Africa/Casablanca')->month)
            ->whereNotNull('heure_depart')
            ->get();

        $tempsTotal = 0;
        foreach ($pointages as $pointage) {
            if ($pointage->heure_arrivee && $pointage->heure_depart) {
                $arrivee = Carbon::parse($pointage->heure_arrivee);
                $depart = Carbon::parse($pointage->heure_depart);
                $tempsTotal += $arrivee->diffInMinutes($depart);
            }
        }

        $heures = floor($tempsTotal / 60);
        $minutes = $tempsTotal % 60;

        return sprintf('%d h %02d min', $heures, $minutes);
    }

    /**
     * Corriger un pointage (pour les administrateurs).
     */
    public function corriger(Request $request, $id)
    {
        $utilisateur = auth()->user();

        if (!($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1'))) {
            abort(403, 'Accès non autorisé pour la correction.');
        }

        $donneesValidees = $request->validate([
            'heure_arrivee' => 'required|date',
            'heure_depart' => 'nullable|date|after:heure_arrivee',
            'description' => 'nullable|string|max:500',
            'localisation' => 'nullable|string|max:255',
            'user_latitude' => 'nullable|numeric|between:-90,90',
            'user_longitude' => 'nullable|numeric|between:-180,180',
        ]);

        try {
            $pointage = SuivrePointage::findOrFail($id);

            $donneesValidees['heure_arrivee'] = Carbon::parse($donneesValidees['heure_arrivee'], 'Africa/Casablanca');
            if (!empty($donneesValidees['heure_depart'])) {
                $donneesValidees['heure_depart'] = Carbon::parse($donneesValidees['heure_depart'], 'Africa/Casablanca');
            }

            $donneesValidees['date_pointage'] = $donneesValidees['heure_arrivee']->copy()->startOfDay();

            $pointage->update($donneesValidees);

            Log::info('Pointage corrigé', [
                'admin_id' => Auth::id(),
                'pointage_id' => $id,
                'modifications' => $donneesValidees
            ]);

            return redirect()->back()->with('success', 'Pointage corrigé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la correction du pointage', [
                'admin_id' => Auth::id(),
                'pointage_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la correction.');
        }
    }
}