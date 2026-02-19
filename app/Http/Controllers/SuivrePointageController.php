<?php

namespace App\Http\Controllers;

use App\Models\SuivrePointage;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SuivrePointageController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permission:pointage-list', ['only' => ['index', 'show']]);
         $this->retardService = new \App\Services\RetardCongeService();
    }

    /**
     * Afficher la liste des pointages avec filtres avancés.
     */
    public function index(Request $request)
    {
        $utilisateur = auth()->user();
        $isAdmin = $utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin');

        $requete = SuivrePointage::with('user')->whereHas('user', function ($query) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'client');
            });
        });

        $users = User::whereDoesntHave('roles', function ($q) {
                $q->where('name', 'client');
            })
            ->orderBy('name')
            ->get();

        $queryParams = $request->except('page');

        if ($recherche = $request->input('search')) {
            $requete->whereHas('user', function ($query) use ($recherche) {
                $query->where('name', 'like', "%{$recherche}%");
            })->orWhereDate('date_pointage', 'like', "%{$recherche}%");
        }

        if ($type = $request->input('type_pointage')) {
            $requete->where('type', $type);
        }

        if ($isAdmin && $justifStatus = $request->input('justificatif_status')) {
            $requete->where('type', 'absence');
            switch ($justifStatus) {
                case 'non_soumis': $requete->whereNull('justificatif'); break;
                case 'en_attente': $requete->whereNotNull('justificatif')->where('justificatif_valide', false); break;
                case 'valide': $requete->where('justificatif_valide', true); break;
            }
        }

        if ($periode = $request->input('periode')) {
            switch ($periode) {
                case 'today': $requete->whereDate('date_pointage', Carbon::today('Africa/Casablanca')); break;
                case 'yesterday': $requete->whereDate('date_pointage', Carbon::yesterday('Africa/Casablanca')); break;
                case 'this_week': $requete->whereBetween('date_pointage', [Carbon::now('Africa/Casablanca')->startOfWeek(), Carbon::now('Africa/Casablanca')->endOfWeek()]); break;
                case 'last_week': $requete->whereBetween('date_pointage', [Carbon::now('Africa/Casablanca')->subWeek()->startOfWeek(), Carbon::now('Africa/Casablanca')->subWeek()->endOfWeek()]); break;
                case 'this_month': $requete->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year); break;
                case 'last_month': $requete->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->subMonth()->month)->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->subMonth()->year); break;
                case 'this_year': $requete->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year); break;
            }
        }

        if ($dateDebut = $request->input('date_debut')) $requete->whereDate('date_pointage', '>=', $dateDebut);
        if ($dateFin = $request->input('date_fin')) $requete->whereDate('date_pointage', '<=', $dateFin);

        if ($statut = $request->input('statut')) {
            if ($statut === 'en_cours') $requete->where('type', 'presence')->whereNull('heure_depart');
            elseif ($statut === 'termine') $requete->where('type', 'presence')->whereNotNull('heure_depart');
            elseif ($statut === 'retard') $requete->where('type', 'presence')->whereNotNull('heure_arrivee')->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00']);
            elseif ($statut === 'depart_anticipe') $requete->where('type', 'presence')->whereNotNull('heure_depart')->whereRaw('TIME(heure_depart) < ?', ['17:30:00']);
        }

        if ($userId = $request->input('user_id')) {
            if ($userId !== 'all') $requete->where('iduser', $userId);
        }

        $requete->orderBy('date_pointage', 'DESC')->orderBy('created_at', 'DESC');

        if (!$isAdmin) {
            $requete->where('iduser', $utilisateur->id);
        }

        $pointages = $requete->paginate(15)->appends($queryParams);
        $stats = $this->getStatistiques($request, $isAdmin ? null : $utilisateur->id);

        return view('suivre_pointage.index', compact('pointages', 'users', 'stats'));
    }

    /**
     * Obtenir les statistiques (UPDATED avec Congés).
     */
    private function getStatistiques(Request $request, $userId = null)
{
    $query = SuivrePointage::query();
    
    // Filtre utilisateur (prioritaire)
    if ($userId) {
        $query->where('iduser', $userId);
    } elseif ($userIdFromRequest = $request->input('user_id')) {
        // CORRECTION: Appliquer le filtre user_id de la requête
        if ($userIdFromRequest !== 'all') {
            $query->where('iduser', $userIdFromRequest);
        }
    }

    // Appliquer les filtres de période
    if ($periode = $request->input('periode')) {
        switch ($periode) {
            case 'today':
                $query->whereDate('date_pointage', Carbon::today('Africa/Casablanca'));
                break;
            case 'yesterday':
                $query->whereDate('date_pointage', Carbon::yesterday('Africa/Casablanca'));
                break;
            case 'this_week':
                $query->whereBetween('date_pointage', [
                    Carbon::now('Africa/Casablanca')->startOfWeek(),
                    Carbon::now('Africa/Casablanca')->endOfWeek()
                ]);
                break;
            case 'last_week':
                $query->whereBetween('date_pointage', [
                    Carbon::now('Africa/Casablanca')->subWeek()->startOfWeek(),
                    Carbon::now('Africa/Casablanca')->subWeek()->endOfWeek()
                ]);
                break;
            case 'this_month':
                $query->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)
                      ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
                break;
            case 'last_month':
                $query->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->subMonth()->month)
                      ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->subMonth()->year);
                break;
            case 'this_year':
                $query->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
                break;
        }
    } elseif ($request->input('date_debut') || $request->input('date_fin')) {
        if ($dateDebut = $request->input('date_debut')) {
            $query->whereDate('date_pointage', '>=', $dateDebut);
        }
        if ($dateFin = $request->input('date_fin')) {
            $query->whereDate('date_pointage', '<=', $dateFin);
        }
    } else {
        // Par défaut: ce mois
        $query->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)
              ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
    }

    // Appliquer le filtre de type de pointage
    if ($type = $request->input('type_pointage')) {
        $query->where('type', $type);
    }

    // Appliquer le filtre de statut
    if ($statut = $request->input('statut')) {
        if ($statut === 'en_cours') {
            $query->where('type', 'presence')->whereNull('heure_depart');
        } elseif ($statut === 'termine') {
            $query->where('type', 'presence')->whereNotNull('heure_depart');
        } elseif ($statut === 'retard') {
            $query->where('type', 'presence')->whereNotNull('heure_arrivee')
                  ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00']);
        } elseif ($statut === 'depart_anticipe') {
            $query->where('type', 'presence')->whereNotNull('heure_depart')
                  ->whereRaw('TIME(heure_depart) < ?', ['17:30:00']);
        }
    }

    $totalPointages = $query->count();
    
    // Présences seulement
    $queryPresence = $query->clone()->where('type', 'presence');
    $pointagesComplets = $queryPresence->clone()->whereNotNull('heure_depart')->count();
    $pointagesEnCours = $queryPresence->clone()->whereNull('heure_depart')->count();
    
    $retards = $queryPresence->clone()
        ->whereNotNull('heure_arrivee')
        ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
        ->where(function($q) {
            $q->whereNull('justificatif_retard')
              ->orWhere('retard_justifie', false);
        })
        ->count();

    $departsAnticipes = $queryPresence->clone()
        ->whereNotNull('heure_depart')
        ->whereRaw('TIME(heure_depart) < ?', ['17:30:00'])
        ->count();

    // Absences et Congés
    $absences = $query->clone()->where('type', 'absence')->count();
    $conges = $query->clone()->where('type', 'conge')->count();

    // Temps total travaillé
    $pointagesTermines = $queryPresence->clone()->whereNotNull('heure_depart')->get();
    $tempsTotal = 0;
    foreach ($pointagesTermines as $pointage) {
        if ($pointage->heure_arrivee && $pointage->heure_depart) {
            $arrivee = Carbon::parse($pointage->heure_arrivee);
            $depart = Carbon::parse($pointage->heure_depart);
            $tempsTotal += $arrivee->diffInMinutes($depart);
        }
    }

    $heures = floor($tempsTotal / 60);
    $minutes = $tempsTotal % 60;

    return [
        'total_pointages' => $totalPointages,
        'pointages_complets' => $pointagesComplets,
        'pointages_en_cours' => $pointagesEnCours,
        'retards' => $retards,
        'departs_anticipes' => $departsAnticipes,
        'absences' => $absences,
        'conges' => $conges,
        'temps_total' => sprintf('%d h %02d min', $heures, $minutes),
        'temps_moyen' => $pointagesComplets > 0 ? sprintf('%d h %02d min', floor($tempsTotal / $pointagesComplets / 60), ($tempsTotal / $pointagesComplets) % 60) : '0 h 00 min',
    ];
}

    public function afficherAlerteRetard()
{
    $user = auth()->user();
    $service = new \App\Services\RetardCongeService();
    $alerte = $service->verifierAlerteRetard($user->id);
    
    return view('components.alerte-retard', compact('alerte'));
}

    /**
     * Exporter les pointages en Excel/CSV.
     */
    public function exporterExcel(Request $request)
    {
        $utilisateur = auth()->user();
        $isAdmin = $utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin');

        if (!$isAdmin) {
            abort(403, 'Accès non autorisé.');
        }

        $requete = SuivrePointage::with('user');
        $this->appliquerFiltres($requete, $request);

        $pointages = $requete->orderBy('date_pointage', 'DESC')->get();

        $filename = 'pointages_' . Carbon::now('Africa/Casablanca')->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pointages) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'Utilisateur',
                'Date',
                'Type',
                'Heure Arrivée',
                'Heure Départ',
                'Durée (minutes)',
                'Statut',
                'Retard',
                'Départ Anticipé',
                'Description'
            ], ';');

            foreach ($pointages as $pointage) {
                $duree = '';
                $retard = 'Non';
                $departAnticipe = 'Non';
                $typeLabel = match($pointage->type) {
                    'presence' => 'Présence',
                    'absence' => 'Absence',
                    'conge' => 'Congé',
                    default => $pointage->type
                };

                if ($pointage->heure_arrivee && $pointage->heure_depart) {
                    $arrivee = Carbon::parse($pointage->heure_arrivee);
                    $depart = Carbon::parse($pointage->heure_depart);
                    $duree = $arrivee->diffInMinutes($depart);
                }

                if ($pointage->heure_arrivee) {
                    $arriveeTime = Carbon::parse($pointage->heure_arrivee);
                    $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                    if ($arriveeTime->greaterThan($expectedArrivee)) {
                        $retard = 'Oui';
                    }
                }

                if ($pointage->heure_depart) {
                    $departTime = Carbon::parse($pointage->heure_depart);
                    $expectedDepart = Carbon::parse($departTime->format('Y-m-d') . ' 17:30:00');
                    if ($departTime->lessThan($expectedDepart)) {
                        $departAnticipe = 'Oui';
                    }
                }

                fputcsv($file, [
                    $pointage->user->name,
                    $pointage->date_pointage ? $pointage->date_pointage->format('d/m/Y') : '',
                    $typeLabel,
                    $pointage->heure_arrivee ? Carbon::parse($pointage->heure_arrivee)->format('H:i') : '',
                    $pointage->heure_depart ? Carbon::parse($pointage->heure_depart)->format('H:i') : '',
                    $duree,
                    $pointage->heure_depart ? 'Terminé' : ($pointage->type === 'presence' ? 'En cours' : '-'),
                    $retard,
                    $departAnticipe,
                    $pointage->description ?? ''
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporter les pointages en PDF.
     */


    
    public function exporterPdf(Request $request)
{
    $utilisateur = auth()->user();
    $isAdmin = $utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin');

    if (!$isAdmin) {
        abort(403, 'Accès non autorisé.');
    }

    $requete = SuivrePointage::with('user');
    $this->appliquerFiltres($requete, $request);

    $pointages = $requete->orderBy('date_pointage', 'ASC')->get();
    
    // IMPORTANT: Passer la request pour appliquer les mêmes filtres aux stats
    $stats = $this->getStatistiques($request);

    $pdf = Pdf::loadView('suivre_pointage.export_pdf', compact('pointages', 'stats'));
    
    $filename = 'pointages_' . Carbon::now('Africa/Casablanca')->format('Y-m-d_His') . '.pdf';
    
    return $pdf->download($filename);
}

    /**
     * Appliquer les filtres à une requête.
     */
    private function appliquerFiltres($requete, Request $request)
{
    if ($recherche = $request->input('search')) {
        $requete->whereHas('user', function ($query) use ($recherche) {
            $query->where('name', 'like', "%{$recherche}%");
        });
    }

    if ($periode = $request->input('periode')) {
        switch ($periode) {
            case 'today':
                $requete->whereDate('date_pointage', Carbon::today('Africa/Casablanca'));
                break;
            case 'this_week':
                $requete->whereBetween('date_pointage', [
                    Carbon::now('Africa/Casablanca')->startOfWeek(),
                    Carbon::now('Africa/Casablanca')->endOfWeek()
                ]);
                break;
            case 'this_month':
                $requete->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)
                       ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
                break;
            // ➕ AJOUT des périodes manquantes
            case 'yesterday':
                $requete->whereDate('date_pointage', Carbon::yesterday('Africa/Casablanca'));
                break;
            case 'last_week':
                $requete->whereBetween('date_pointage', [
                    Carbon::now('Africa/Casablanca')->subWeek()->startOfWeek(),
                    Carbon::now('Africa/Casablanca')->subWeek()->endOfWeek()
                ]);
                break;
            case 'last_month':
                $requete->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->subMonth()->month)
                       ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->subMonth()->year);
                break;
            case 'this_year':
                $requete->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
                break;
        }
    }

    if ($dateDebut = $request->input('date_debut')) {
        $requete->whereDate('date_pointage', '>=', $dateDebut);
    }
    if ($dateFin = $request->input('date_fin')) {
        $requete->whereDate('date_pointage', '<=', $dateFin);
    }

    // ➕ AJOUT: Filtre type_pointage
    if ($type = $request->input('type_pointage')) {
        $requete->where('type', $type);
    }

    if ($statut = $request->input('statut')) {
        if ($statut === 'en_cours') {
            $requete->where('type', 'presence')->whereNull('heure_depart');
        } elseif ($statut === 'termine') {
            $requete->where('type', 'presence')->whereNotNull('heure_depart');
        } elseif ($statut === 'retard') {
            $requete->where('type', 'presence')->whereNotNull('heure_arrivee')
                   ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00']);
        } elseif ($statut === 'depart_anticipe') {
            $requete->where('type', 'presence')->whereNotNull('heure_depart')
                   ->whereRaw('TIME(heure_depart) < ?', ['17:30:00']);
        }
    }

    // ➕ AJOUT: Filtre justificatif_status (CRUCIAL - c'était manquant!)
    if ($justifStatus = $request->input('justificatif_status')) {
        $requete->where('type', 'absence');
        switch ($justifStatus) {
            case 'non_soumis':
                $requete->whereNull('justificatif');
                break;
            case 'en_attente':
                $requete->whereNotNull('justificatif')->where('justificatif_valide', false);
                break;
            case 'valide':
                $requete->where('justificatif_valide', true);
                break;
        }
    }

    if ($userId = $request->input('user_id')) {
        if ($userId !== 'all') {
            $requete->where('iduser', $userId);
        }
    }
}
    /**
     * API pour les données de charts.
     */
    public function getChartData(Request $request)
    {
        $utilisateur = auth()->user();
        $isAdmin = $utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin');

        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $utilisateur->id);
        }

        $dateDebut = Carbon::now('Africa/Casablanca')->subDays(30);
        $query->where('date_pointage', '>=', $dateDebut);

        $pointages = $query->orderBy('date_pointage')->get();

        $chartData = [
            'dates' => [],
            'heures_travaillees' => [],
            'retards' => [],
        ];

        $groupedByDate = $pointages->groupBy(function($pointage) {
            return Carbon::parse($pointage->date_pointage)->format('Y-m-d');
        });

        foreach ($groupedByDate as $date => $pointagesJour) {
            $chartData['dates'][] = Carbon::parse($date)->format('d/m');
            
            $tempsTotal = 0;
            $retardsJour = 0;

            foreach ($pointagesJour as $pointage) {
                if ($pointage->type === 'presence' && $pointage->heure_arrivee && $pointage->heure_depart) {
                    $arrivee = Carbon::parse($pointage->heure_arrivee);
                    $depart = Carbon::parse($pointage->heure_depart);
                    $tempsTotal += $arrivee->diffInMinutes($depart) / 60;
                }

                if ($pointage->type === 'presence' && $pointage->heure_arrivee) {
                    $arriveeTime = Carbon::parse($pointage->heure_arrivee);
                    $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                    if ($arriveeTime->greaterThan($expectedArrivee)) {
                        $retardsJour++;
                    }
                }
            }

            $chartData['heures_travaillees'][] = round($tempsTotal, 2);
            $chartData['retards'][] = $retardsJour;
        }

        return response()->json($chartData);
    }

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

        if (request()->ajax()) {
            $isLateForArrival = false;
            $isEarlyDeparture = false;
            $duree = null;
            $retardMinutes = 0;
            $departMinutes = 0;

            if ($pointage->heure_arrivee) {
                $arriveeTime = Carbon::parse($pointage->heure_arrivee);
                $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                if ($arriveeTime->greaterThan($expectedArrivee)) {
                    $isLateForArrival = true;
                    $retardMinutes = $arriveeTime->diffInMinutes($expectedArrivee);
                }
            }

            if ($pointage->heure_depart) {
                $departTime = Carbon::parse($pointage->heure_depart);
                $expectedDepart = Carbon::parse($departTime->format('Y-m-d') . ' 17:30:00');
                if ($departTime->lessThan($expectedDepart)) {
                    $isEarlyDeparture = true;
                    $departMinutes = $expectedDepart->diffInMinutes($departTime);
                }
            }

            if ($pointage->heure_arrivee && $pointage->heure_depart) {
                $arrivee = Carbon::parse($pointage->heure_arrivee);
                $depart = Carbon::parse($pointage->heure_depart);
                $minutes = $arrivee->diffInMinutes($depart);
                $heures = floor($minutes / 60);
                $mins = $minutes % 60;
                $duree = sprintf('%d h %02d min', $heures, $mins);
            }

            return response()->json([
                'user' => $pointage->user->name,
                'date' => $pointage->date_pointage ? $pointage->date_pointage->format('d/m/Y') : 'N/A',
                'type' => $pointage->type,
                'heure_arrivee' => $pointage->heure_arrivee ? Carbon::parse($pointage->heure_arrivee)->format('H:i') : null,
                'heure_depart' => $pointage->heure_depart ? Carbon::parse($pointage->heure_depart)->format('H:i') : null,
                'duree' => $duree,
                'is_late' => $isLateForArrival,
                'retard_minutes' => $retardMinutes,
                'is_early_departure' => $isEarlyDeparture,
                'depart_minutes' => $departMinutes,
                'localisation' => $pointage->localisation,
                'description' => $pointage->description,
                'justificatif' => $pointage->justificatif,
                'justificatif_valide' => $pointage->justificatif_valide,
                'created_at' => $pointage->created_at->format('d/m/Y H:i'),
            ]);
        }

        return view('suivre_pointage.show', compact('pointage'));
    }

    /**
     * Corriger un pointage.
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


    public function soumettreJustificatif(Request $request, $id)
{
    $request->validate([
        'justificatif' => 'required|string|max:1000',
        'justificatif_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
    ]);

    $pointage = SuivrePointage::findOrFail($id);
    
    // Vérifier que c'est bien une absence
    if ($pointage->type !== 'absence') {
        return back()->with('error', 'Vous ne pouvez justifier qu\'une absence.');
    }

    // Vérifier que l'utilisateur peut modifier ce pointage
    if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']) && $pointage->iduser !== auth()->id()) {
        abort(403, 'Accès non autorisé.');
    }

    $data = [
        'justificatif' => $request->justificatif,
        'justificatif_soumis_at' => now(),
    ];

    // Gérer upload du fichier
    if ($request->hasFile('justificatif_file')) {
        $file = $request->file('justificatif_file');
        $filename = 'justif_' . $pointage->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('justificatifs', $filename, 'public');
        $data['justificatif_file'] = $path;
    }

    $pointage->update($data);

    Log::info('Justificatif soumis', [
        'pointage_id' => $pointage->id,
        'user_id' => auth()->id(),
        'justificatif' => $request->justificatif
    ]);

    return back()->with('success', 'Justificatif soumis avec succès.');
}

/**
 * Valider/Rejeter un justificatif (Admin uniquement)
 */
public function validerJustificatif(Request $request, $id)
{
    if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin'])) {
        abort(403, 'Accès non autorisé.');
    }

    $request->validate([
        'action' => 'required|in:valider,rejeter',
        'commentaire_admin' => 'nullable|string|max:500',
    ]);

    $pointage = SuivrePointage::findOrFail($id);
    
    $valide = $request->action === 'valider';
    
    $pointage->update([
        'justificatif_valide' => $valide,
        'description' => $pointage->description . "\n[Admin] " . ($request->commentaire_admin ?? ($valide ? 'Justificatif validé' : 'Justificatif rejeté'))
    ]);

    Log::info('Justificatif ' . $request->action, [
        'pointage_id' => $pointage->id,
        'admin_id' => auth()->id(),
        'decision' => $valide
    ]);

    return back()->with('success', 'Justificatif ' . ($valide ? 'validé' : 'rejeté') . ' avec succès.');
}

/**
 * Télécharger le fichier justificatif
 */
public function telechargerJustificatif($id)
{
    $pointage = SuivrePointage::findOrFail($id);
    
    // Vérifier accès
    if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']) && $pointage->iduser !== auth()->id()) {
        abort(403, 'Accès non autorisé.');
    }

    if (!$pointage->justificatif_file) {
        abort(404, 'Aucun fichier justificatif trouvé.');
    }

    return response()->download(storage_path('app/public/' . $pointage->justificatif_file));
}


public function soumettreJustificatifRetard(Request $request, $id)
{
    $request->validate([
        'justificatif_retard' => 'required|string|max:1000',
        'justificatif_retard_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    $pointage = SuivrePointage::findOrFail($id);
    
    // Vérifier que c'est bien une présence en retard
    if ($pointage->type !== 'presence' || !$pointage->isLate()) {
        return back()->with('error', 'Ce pointage n\'est pas en retard.');
    }

    // Vérifier les permissions
    if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']) && $pointage->iduser !== auth()->id()) {
        abort(403, 'Accès non autorisé.');
    }

    $data = [
        'justificatif_retard' => $request->justificatif_retard,
        'justificatif_retard_soumis_at' => now(),
    ];

    // Gérer upload du fichier
    if ($request->hasFile('justificatif_retard_file')) {
        $file = $request->file('justificatif_retard_file');
        $filename = 'justif_retard_' . $pointage->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('justificatifs_retard', $filename, 'public');
        $data['justificatif_retard_file'] = $path;
    }

    $pointage->update($data);

    Log::info('Justificatif de retard soumis', [
        'pointage_id' => $pointage->id,
        'user_id' => auth()->id(),
        'retard_minutes' => $pointage->getRetardMinutes()
    ]);

    return back()->with('success', 'Justificatif de retard soumis avec succès.');
}

/**
 * Valider/Rejeter un justificatif de retard (Admin uniquement)
 */
public function validerJustificatifRetard(Request $request, $id)
{
    if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin'])) {
        abort(403, 'Accès non autorisé.');
    }

    $request->validate([
        'action' => 'required|in:valider,rejeter',
        'commentaire_admin' => 'nullable|string|max:500',
    ]);

    $pointage = SuivrePointage::findOrFail($id);
    
    $valide = $request->action === 'valider';
    
    $commentaire = "\n[Admin - Retard] " . ($request->commentaire_admin ?? ($valide ? 'Retard justifié' : 'Retard non justifié'));
    
    $pointage->update([
        'retard_justifie' => $valide,
        'description' => ($pointage->description ?? '') . $commentaire
    ]);

    Log::info('Justificatif de retard ' . $request->action, [
        'pointage_id' => $pointage->id,
        'admin_id' => auth()->id(),
        'decision' => $valide
    ]);

    return back()->with('success', 'Justificatif de retard ' . ($valide ? 'validé' : 'rejeté') . ' avec succès.');
}

/**
 * Télécharger le fichier justificatif de retard
 */
public function telechargerJustificatifRetard($id)
{
    $pointage = SuivrePointage::findOrFail($id);
    
    if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin']) && $pointage->iduser !== auth()->id()) {
        abort(403, 'Accès non autorisé.');
    }

    if (!$pointage->justificatif_retard_file) {
        abort(404, 'Aucun fichier justificatif trouvé.');
    }

    return response()->download(storage_path('app/public/' . $pointage->justificatif_retard_file));
}
}