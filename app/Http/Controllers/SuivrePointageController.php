<?php

namespace App\Http\Controllers;

use App\Models\SuivrePointage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Assurez-vous que Log est importé

class SuivrePointageController extends Controller
{
    /**
     * Constructeur du contrôleur avec gestion des permissions.
     */
    public function __construct()
    {
        $this->middleware('permission:pointage-list', ['only' => ['index', 'show']]);
        // Aucune permission spécifique pour 'pointer' ici, car la restriction par rôle est faite dans la méthode elle-même.
    }

    /**
     * Afficher la liste des pointages
     */
    public function index()
    {
        $utilisateur = auth()->user();

        // Récupérer le pointage en cours s'il existe (seulement pour les utilisateurs non-admin).
        $pointageEnCours = null;
        if (!($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1'))) {
            $pointageEnCours = SuivrePointage::where('iduser', Auth::id())
                ->whereNull('heure_depart')
                ->first();
        }

        $requete = SuivrePointage::with('user');

        // Filtrage par recherche si présent
        if ($recherche = request('search')) {
            $requete->whereHas('user', function ($query) use ($recherche) {
                $query->where('name', 'like', "%{$recherche}%");
            })
            ->orWhereDate('created_at', 'like', "%{$recherche}%");
        }

        // Filtrage par date de début si présent (Date Fin supprimée)
        if ($dateDebut = request('date_debut')) {
            $requete->whereDate('created_at', '>=', $dateDebut);
        }

        // Filtrage par statut
        if ($statut = request('statut')) {
            if ($statut === 'en_cours') {
                $requete->whereNull('heure_depart');
            } elseif ($statut === 'termine') {
                $requete->whereNotNull('heure_depart');
            }
        }

        // Tri des données - les éléments avec created_at en premier
        $requete->orderByRaw("CASE WHEN created_at IS NULL THEN 1 ELSE 0 END, created_at DESC");

        // Récupération des données selon le rôle utilisateur
        if ($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1')) {
            // Admins voient tous les pointages
            $pointages = $requete->paginate(10);
        } else {
            // Autres utilisateurs voient seulement leurs propres pointages
            $pointages = $requete->where('iduser', $utilisateur->id)->paginate(10);
        }

        return view('suivre_pointage.index', compact('pointages', 'pointageEnCours'));
    }

    /**
     * Effectuer un pointage (arrivée ou départ)
     */
    public function pointer(Request $request)
    {
        $utilisateur = auth()->user();

        // Restriction explicite pour les rôles 'Admin' et 
        if ($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1')) {
            return redirect()->back()->with('error', 'En tant qu\'administrateur, vous n\'êtes pas autorisé à pointer.');
        }

        // Validation des données d'entrée
        $donneesValidees = $request->validate([
            'description' => 'nullable|string|max:500',
            'localisation' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Utilisez Carbon pour obtenir l'heure actuelle de Casablanca
            $casablancaNow = Carbon::now('Africa/Casablanca');
            $casablancaToday = Carbon::today('Africa/Casablanca');

            // Vérifier s'il y a un pointage en cours pour l'utilisateur ET pour la date d'aujourd'hui (Casablanca)
            $pointageEnCours = SuivrePointage::where('iduser', Auth::id())
                ->whereDate('heure_arrivee', $casablancaToday) // Utilisez la date d'aujourd'hui de Casablanca
                ->whereNull('heure_depart')
                ->first();

            if ($pointageEnCours) {
                // Pointage de départ: L'utilisateur est déjà arrivé et n'est pas encore parti.
                $pointageEnCours->update([
                    'heure_depart' => $casablancaNow, // Enregistre l'heure de départ actuelle de Casablanca
                    'description' => $donneesValidees['description'] ?? $pointageEnCours->description,
                ]);

                $message = 'Pointage de départ enregistré avec succès.';
                Log::info('Pointage de départ', ['user_id' => Auth::id(), 'pointage_id' => $pointageEnCours->id]);
            } else {
                // Pointage d'arrivée: L'utilisateur n'a pas encore pointé son arrivée aujourd'hui.
                // Vérifier s'il a déjà pointé son arrivée ET son départ aujourd'hui (Casablanca)
                $alreadyClockedOutToday = SuivrePointage::where('iduser', Auth::id())
                    ->whereDate('heure_arrivee', $casablancaToday) // Utilisez la date d'aujourd'hui de Casablanca
                    ->whereNotNull('heure_depart')
                    ->exists();

                if ($alreadyClockedOutToday) {
                    DB::rollBack(); // Pas besoin de créer un pointage
                    return redirect()->back()->with('info', 'Vous avez déjà pointé votre arrivée et votre départ pour aujourd\'hui.');
                }

                $nouveauPointage = SuivrePointage::create([
                    'iduser' => Auth::id(),
                    'heure_arrivee' => $casablancaNow, // Enregistre l'heure d'arrivée actuelle de Casablanca
                    'date_pointage' => $casablancaToday, // La date du pointage sera aussi celle de Casablanca
                    'description' => $donneesValidees['description'] ?? null,
                    'localisation' => $donneesValidees['localisation'] ?? null,
                ]);

                $message = 'Pointage d\'arrivée enregistré avec succès.';
                Log::info('Pointage d\'arrivée', ['user_id' => Auth::id(), 'pointage_id' => $nouveauPointage->id]);
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du pointage', ['user_id' => Auth::id(), 'error_message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors du pointage. Veuillez réessayer.');
        }
    }

    /**
     * Afficher les détails d'un pointage
     */
    public function show($id)
    {
        $pointage = SuivrePointage::with('user')->findOrFail($id);
        
        // Vérifier les permissions d'accès
        $utilisateur = auth()->user();
        if (!($utilisateur->hasRole('Admin') || $utilisateur->hasRole('Admin1')) && $pointage->iduser !== $utilisateur->id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('suivre_pointage.show', compact('pointage'));
    }

    /**
     * Obtenir les statistiques de pointage pour un utilisateur
     */
    public function statistiques(Request $request)
    {
        $utilisateur = auth()->user();
        // Assurez-vous que 'mois' est interprété dans la timezone de Casablanca si nécessaire pour la logique
        $moisActuel = $request->get('mois', Carbon::now('Africa/Casablanca')->format('Y-m'));

        $requete = SuivrePointage::where('iduser', $utilisateur->id)
            ->whereYear('created_at', '=', Carbon::parse($moisActuel, 'Africa/Casablanca')->year) // Parse avec la timezone
            ->whereMonth('created_at', '=', Carbon::parse($moisActuel, 'Africa/Casablanca')->month); // Parse avec la timezone

        $statistiques = [
            'total_pointages' => $requete->count(),
            'pointages_complets' => $requete->clone()->whereNotNull('heure_depart')->count(),
            'pointages_en_cours' => $requete->clone()->whereNull('heure_depart')->count(),
            'temps_total_travaille' => $this->calculerTempsTotalTravaille($utilisateur->id, $moisActuel),
        ];

        return response()->json($statistiques);
    }

    /**
     * Calculer le temps total travaillé pour un utilisateur
     */
    private function calculerTempsTotalTravaille($utilisateurId, $mois)
    {
        $pointages = SuivrePointage::where('iduser', $utilisateurId)
            ->whereYear('created_at', '=', Carbon::parse($mois, 'Africa/Casablanca')->year) // Parse avec la timezone
            ->whereMonth('created_at', '=', Carbon::parse($mois, 'Africa/Casablanca')->month) // Parse avec la timezone
            ->whereNotNull('heure_depart')
            ->get();

        $tempsTotal = 0;
        foreach ($pointages as $pointage) {
            if ($pointage->heure_arrivee && $pointage->heure_depart) {
                // Carbon les dates pour les calculs de durée.
                // Si les heures sont déjà stockées en UTC et que votre app timezone est Casablanca,
                // Carbon::parse() les convertira automatiquement pour le calcul.
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
     * Corriger un pointage (pour les administrateurs)
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
        ]);

        try {
            $pointage = SuivrePointage::findOrFail($id);
            
            // Convertir les heures d'arrivée et de départ validées en objets Carbon avec la timezone de Casablanca
            $donneesValidees['heure_arrivee'] = Carbon::parse($donneesValidees['heure_arrivee'], 'Africa/Casablanca');
            if (!empty($donneesValidees['heure_depart'])) {
                $donneesValidees['heure_depart'] = Carbon::parse($donneesValidees['heure_depart'], 'Africa/Casablanca');
            }

            // Pour s'assurer que la date_pointage est mise à jour avec la date de Casablanca de l'heure d'arrivée corrigée
            $donneesValidees['date_pointage'] = $donneesValidees['heure_arrivee']->copy()->startOfDay(); // Ou ->toDateString();

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