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
    }

    /**
     * Afficher la liste des pointages avec filtres avancés.
     */
    public function index(Request $request)
    {
        $utilisateur = auth()->user();
        $isAdmin = $utilisateur->hasRole('Sup_Admin') || $utilisateur->hasRole('Custom_Admin');

        $pointageEnCours = null;
        if (!$isAdmin) {
            $pointageEnCours = SuivrePointage::where('iduser', Auth::id())
                ->whereDate('date_pointage', Carbon::today('Africa/Casablanca'))
                ->whereNull('heure_depart')
                ->first();
        }

        $requete = SuivrePointage::with('user');
        $users = User::orderBy('name')->get();
        $queryParams = $request->except('page');

        // Filtre par recherche
        if ($recherche = $request->input('search')) {
            $requete->whereHas('user', function ($query) use ($recherche) {
                $query->where('name', 'like', "%{$recherche}%");
            })->orWhereDate('date_pointage', 'like', "%{$recherche}%");
        }

        // Filtre par période (mois/semaine)
        if ($periode = $request->input('periode')) {
            switch ($periode) {
                case 'today':
                    $requete->whereDate('date_pointage', Carbon::today('Africa/Casablanca'));
                    break;
                case 'yesterday':
                    $requete->whereDate('date_pointage', Carbon::yesterday('Africa/Casablanca'));
                    break;
                case 'this_week':
                    $requete->whereBetween('date_pointage', [
                        Carbon::now('Africa/Casablanca')->startOfWeek(),
                        Carbon::now('Africa/Casablanca')->endOfWeek()
                    ]);
                    break;
                case 'last_week':
                    $requete->whereBetween('date_pointage', [
                        Carbon::now('Africa/Casablanca')->subWeek()->startOfWeek(),
                        Carbon::now('Africa/Casablanca')->subWeek()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $requete->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)
                           ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
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

        // Filtre par plage de dates personnalisée
        if ($dateDebut = $request->input('date_debut')) {
            $requete->whereDate('date_pointage', '>=', $dateDebut);
        }
        if ($dateFin = $request->input('date_fin')) {
            $requete->whereDate('date_pointage', '<=', $dateFin);
        }

        // Filtre par statut
        if ($statut = $request->input('statut')) {
            if ($statut === 'en_cours') {
                $requete->whereNull('heure_depart');
            } elseif ($statut === 'termine') {
                $requete->whereNotNull('heure_depart');
            } elseif ($statut === 'retard') {
                $requete->whereNotNull('heure_arrivee')
                       ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00']);
            } elseif ($statut === 'depart_anticipe') {
                $requete->whereNotNull('heure_depart')
                       ->whereRaw('TIME(heure_depart) < ?', ['17:30:00']);
            }
        }

        // Filtre par utilisateur
        if ($userId = $request->input('user_id')) {
            if ($userId !== 'all') {
                $requete->where('iduser', $userId);
            }
        }

        $requete->orderBy('date_pointage', 'DESC')->orderBy('heure_arrivee', 'DESC');

        // Restriction pour non-admin
        if (!$isAdmin) {
            $requete->where('iduser', $utilisateur->id);
        }

        $pointages = $requete->paginate(15)->appends($queryParams);

        // Statistiques pour le dashboard
        $stats = $this->getStatistiques($request, $isAdmin ? null : $utilisateur->id);

        return view('suivre_pointage.index', compact(
            'pointages',
            'pointageEnCours',
            'users',
            'stats'
        ));
    }

    /**
     * Obtenir les statistiques.
     */
    private function getStatistiques(Request $request, $userId = null)
    {
        $query = SuivrePointage::query();
        
        if ($userId) {
            $query->where('iduser', $userId);
        }

        // Appliquer les mêmes filtres que l'index
        if ($periode = $request->input('periode')) {
            switch ($periode) {
                case 'today':
                    $query->whereDate('date_pointage', Carbon::today('Africa/Casablanca'));
                    break;
                case 'this_week':
                    $query->whereBetween('date_pointage', [
                        Carbon::now('Africa/Casablanca')->startOfWeek(),
                        Carbon::now('Africa/Casablanca')->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)
                          ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
                    break;
            }
        } else {
            // Par défaut: ce mois
            $query->whereMonth('date_pointage', Carbon::now('Africa/Casablanca')->month)
                  ->whereYear('date_pointage', Carbon::now('Africa/Casablanca')->year);
        }

        $totalPointages = $query->count();
        $pointagesComplets = $query->clone()->whereNotNull('heure_depart')->count();
        $pointagesEnCours = $query->clone()->whereNull('heure_depart')->count();
        
        $retards = $query->clone()
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
            ->count();

        $departsAnticipes = $query->clone()
            ->whereNotNull('heure_depart')
            ->whereRaw('TIME(heure_depart) < ?', ['17:30:00'])
            ->count();

        // Temps total travaillé
        $pointagesTermines = $query->clone()->whereNotNull('heure_depart')->get();
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
            'temps_total' => sprintf('%d h %02d min', $heures, $minutes),
            'temps_moyen' => $pointagesComplets > 0 ? sprintf('%d h %02d min', floor($tempsTotal / $pointagesComplets / 60), ($tempsTotal / $pointagesComplets) % 60) : '0 h 00 min',
        ];
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
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            fputcsv($file, [
                'Utilisateur',
                'Date',
                'Heure Arrivée',
                'Heure Départ',
                'Durée (minutes)',
                'Statut',
                'Retard',
                'Départ Anticipé',
                'Localisation',
                'Description'
            ], ';');

            foreach ($pointages as $pointage) {
                $duree = '';
                $retard = 'Non';
                $departAnticipe = 'Non';

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
                    $pointage->heure_arrivee ? Carbon::parse($pointage->heure_arrivee)->format('H:i') : '',
                    $pointage->heure_depart ? Carbon::parse($pointage->heure_depart)->format('H:i') : '',
                    $duree,
                    $pointage->heure_depart ? 'Terminé' : 'En cours',
                    $retard,
                    $departAnticipe,
                    $pointage->localisation ?? '',
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

        $pointages = $requete->orderBy('date_pointage', 'DESC')->get();
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
            }
        }

        if ($dateDebut = $request->input('date_debut')) {
            $requete->whereDate('date_pointage', '>=', $dateDebut);
        }
        if ($dateFin = $request->input('date_fin')) {
            $requete->whereDate('date_pointage', '<=', $dateFin);
        }

        if ($statut = $request->input('statut')) {
            if ($statut === 'en_cours') {
                $requete->whereNull('heure_depart');
            } elseif ($statut === 'termine') {
                $requete->whereNotNull('heure_depart');
            } elseif ($statut === 'retard') {
                $requete->whereNotNull('heure_arrivee')
                       ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00']);
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

        // Données pour les 30 derniers jours
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
                if ($pointage->heure_arrivee && $pointage->heure_depart) {
                    $arrivee = Carbon::parse($pointage->heure_arrivee);
                    $depart = Carbon::parse($pointage->heure_depart);
                    $tempsTotal += $arrivee->diffInMinutes($depart) / 60;
                }

                if ($pointage->heure_arrivee) {
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
}