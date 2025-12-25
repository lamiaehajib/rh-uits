<?php

namespace App\Http\Controllers;

use App\Models\SuivrePointage;
use App\Models\User;
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
    public function index(Request $request)
    {
        $utilisateur = auth()->user();

        $pointageEnCours = null;
        if (!($utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin'))) {
            $pointageEnCours = SuivrePointage::where('iduser', Auth::id())
                ->whereDate('date_pointage', Carbon::today('Africa/Casablanca'))
                ->whereNull('heure_depart')
                ->first();
        }

        $requete = SuivrePointage::with('user');

        // Get all users for the filter dropdown
        $users = User::orderBy('name')->get();

        // Store all request parameters to append them to pagination links
        $queryParams = $request->except('page'); // Exclude 'page' as paginator handles it

        if ($recherche = $request->input('search')) {
            $requete->whereHas('user', function ($query) use ($recherche) {
                $query->where('name', 'like', "%{$recherche}%");
            })
            ->orWhereDate('date_pointage', 'like', "%{$recherche}%");
        }

        if ($dateDebut = $request->input('date_debut')) {
            $requete->whereDate('date_pointage', '>=', $dateDebut);
        }

        if ($statut = $request->input('statut')) {
            if ($statut === 'en_cours') {
                $requete->whereNull('heure_depart');
            } elseif ($statut === 'termine') {
                $requete->whereNotNull('heure_depart');
            }
        }

        if ($userId = $request->input('user_id')) {
            if ($userId !== 'all') {
                $requete->where('iduser', $userId);
            }
        }

        $requete->orderBy('date_pointage', 'DESC')->orderBy('heure_arrivee', 'DESC');

        if ($utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin')) {
            // Apply appends() here
            $pointages = $requete->paginate(10)->appends($queryParams);
        } else {
            // Apply appends() here
            $pointages = $requete->where('iduser', $utilisateur->id)->paginate(10)->appends($queryParams);
        }

        // Also pass the current filter values back to the view to pre-fill the form
        $currentSearch = $request->input('search');
        $currentDateDebut = $request->input('date_debut');
        $currentStatut = $request->input('statut');
        $currentUserId = $request->input('user_id');


        return view('suivre_pointage.index', compact('pointages', 'pointageEnCours', 'users',
            'currentSearch', 'currentDateDebut', 'currentStatut', 'currentUserId'
        ));
    }

    /**
     * Effectuer un pointage (arrivée ou départ).
     */
    
    /**
     * Calcule la distance grand cercle entre deux points sur une sphère.
     */
   
    /**
     * Afficher les détails d'un pointage.
     */
    public function show($id)
    {
        $pointage = SuivrePointage::with('user')->findOrFail($id);

        $utilisateur = auth()->user();
        if (!($utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin')) && $pointage->iduser !== $utilisateur->id) {
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

        if (!($utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin'))) {
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