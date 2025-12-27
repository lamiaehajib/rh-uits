<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\User;
use App\Models\SuivrePointage;
use App\Models\Tache;
use App\Models\Objectif;
use App\Models\Projet;
use App\Models\RendezVous;
use App\Models\Avancement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        // Récupérer les filtres (par défaut: depuis le début)
        $filterType = $request->input('filter_type', 'all_time'); // all_time, monthly, yearly
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);

        // Créer la période de filtrage
        $dateRange = $this->getDateRange($filterType, $selectedMonth, $selectedYear);

        // Statistiques générales avec filtrage
        $stats = [
            'users' => $this->getUserStats($isAdmin, $dateRange),
            'pointages' => $this->getPointageStats($isAdmin, $user, $dateRange),
            'taches' => $this->getTacheStats($isAdmin, $user, $dateRange),
            'objectifs' => $this->getObjectifStats($isAdmin, $user, $dateRange),
            'clients' => $this->getClientStats($isAdmin, $dateRange),
            'projets' => $this->getProjetStats($isAdmin, $dateRange),
        ];

        // Données pour les charts
        $chartData = [
            'pointages_monthly' => $this->getPointagesMonthlyChart($isAdmin, $user, $dateRange),
            'taches_status' => $this->getTachesStatusChart($isAdmin, $user, $dateRange),
            'objectifs_progress' => $this->getObjectifsProgressChart($isAdmin, $user, $dateRange),
            'projets_status' => $this->getProjetsStatusChart($isAdmin, $dateRange),
            'users_performance' => $this->getUsersPerformanceChart($isAdmin, $dateRange),
            'retards_trend' => $this->getRetardsTrendChart($isAdmin, $user, $dateRange),
        ];

        // Activités récentes
        $recentActivities = [
            'taches' => $this->getRecentTaches($isAdmin, $user, 5, $dateRange),
            'pointages' => $this->getRecentPointages($isAdmin, $user, 5, $dateRange),
            'projets' => $this->getRecentProjets($isAdmin, 5, $dateRange),
            'rendezvous' => $this->getUpcomingRendezVous($isAdmin, 5),
        ];

        // Top performers
        $topPerformers = $isAdmin ? $this->getTopPerformers($dateRange) : null;

        // Alerts et notifications
        $alerts = [
            'retards' => $this->getRetardsAlert($isAdmin, $user, $dateRange),
            'taches_overdue' => $this->getTachesOverdueAlert($isAdmin, $user, $dateRange),
            'objectifs_incomplets' => $this->getObjectifsIncomplets($isAdmin, $user, $dateRange),
        ];

        // Liste des années disponibles pour le filtre
        $availableYears = range(2020, now()->year);

        return view('dashboard.index', compact(
            'stats',
            'chartData',
            'recentActivities',
            'topPerformers',
            'alerts',
            'isAdmin',
            'filterType',
            'selectedMonth',
            'selectedYear',
            'availableYears'
        ));
    }

    // ==================== HELPER: Date Range ====================
    
    private function getDateRange($filterType, $month, $year)
    {
        switch ($filterType) {
            case 'monthly':
                return [
                    'start' => Carbon::createFromDate($year, $month, 1)->startOfMonth(),
                    'end' => Carbon::createFromDate($year, $month, 1)->endOfMonth(),
                    'type' => 'monthly'
                ];
            case 'yearly':
                return [
                    'start' => Carbon::createFromDate($year, 1, 1)->startOfYear(),
                    'end' => Carbon::createFromDate($year, 12, 31)->endOfYear(),
                    'type' => 'yearly'
                ];
            default: // all_time
                return [
                    'start' => null,
                    'end' => null,
                    'type' => 'all_time'
                ];
        }
    }

    // ==================== STATISTIQUES (Modifiées) ====================

    private function getUserStats($isAdmin, $dateRange)
    {
        if (!$isAdmin) return null;

        $query = User::query();
        
        if ($dateRange['start']) {
            $newUsersQuery = User::whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            $newCount = $newUsersQuery->count();
        } else {
            $newCount = User::count();
        }

        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'new_in_period' => $newCount,
        ];
    }

    private function getPointageStats($isAdmin, $user, $dateRange)
    {
        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        // Appliquer le filtre de date
        if ($dateRange['start']) {
            $query->whereBetween('date_pointage', [$dateRange['start'], $dateRange['end']]);
        }

        $total = $query->clone()->count();

        $retards = $query->clone()
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
            ->count();

        $departsAnticipes = $query->clone()
            ->whereNotNull('heure_depart')
            ->whereRaw('TIME(heure_depart) < ?', ['17:30:00'])
            ->count();

        // Calculer temps moyen travaillé
        $pointagesTermines = $query->clone()->whereNotNull('heure_depart')->get();
        $tempsTotal = 0;
        foreach ($pointagesTermines as $pointage) {
            if ($pointage->heure_arrivee && $pointage->heure_depart) {
                $arrivee = Carbon::parse($pointage->heure_arrivee);
                $depart = Carbon::parse($pointage->heure_depart);
                $tempsTotal += $arrivee->diffInMinutes($depart);
            }
        }
        $tempsMoyen = $pointagesTermines->count() > 0 
            ? round($tempsTotal / $pointagesTermines->count() / 60, 2) 
            : 0;

        return [
            'total_period' => $total,
            'retards' => $retards,
            'departs_anticipes' => $departsAnticipes,
            'temps_moyen_heures' => $tempsMoyen,
            'taux_ponctualite' => $total > 0 
                ? round((($total - $retards) / $total) * 100, 2) 
                : 100,
        ];
    }

    private function getTacheStats($isAdmin, $user, $dateRange)
    {
        $query = Tache::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('datedebut', '<=', Carbon::now());
        }

        // Appliquer le filtre de date sur created_at
        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = $query->clone()->count();
        $nouveau = $query->clone()->where('status', 'nouveau')->count();
        $enCours = $query->clone()->where('status', 'en cours')->count();
        $termine = $query->clone()->where('status', 'termine')->count();
        
        $overdue = $query->clone()
            ->where('status', '!=', 'termine')
            ->whereNotNull('date_fin_prevue')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->count();

        return [
            'total' => $total,
            'nouveau' => $nouveau,
            'en_cours' => $enCours,
            'termine' => $termine,
            'overdue' => $overdue,
            'taux_completion' => $total > 0 ? round(($termine / $total) * 100, 2) : 0,
        ];
    }

    private function getObjectifStats($isAdmin, $user, $dateRange)
    {
        $query = Objectif::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // Appliquer le filtre de date
        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        $total = $query->clone()->count();
        $completed = $query->clone()->where('progress', '>=', 100)->count();
        $inProgress = $query->clone()->where('progress', '>', 0)->where('progress', '<', 100)->count();
        
        $overdue = $query->clone()
            ->where('progress', '<', 100)
            ->where('date', '<', Carbon::now())
            ->count();

        $avgProgress = $query->clone()->avg('progress') ?? 0;

        return [
            'total' => $total,
            'completed' => $completed,
            'in_progress' => $inProgress,
            'overdue' => $overdue,
            'avg_progress' => round($avgProgress, 2),
            'taux_reussite' => $total > 0 ? round(($completed / $total) * 100, 2) : 0,
        ];
    }

    private function getClientStats($isAdmin, $dateRange)
    {
        if (!$isAdmin) return null;

        $query = User::role('Client');
        
        if ($dateRange['start']) {
            $newQuery = User::role('Client')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
            $newCount = $newQuery->count();
        } else {
            $newCount = User::role('Client')->count();
        }

        $clients = User::role('Client')->get();
        
        return [
            'total' => $clients->count(),
            'particuliers' => $clients->where('type_client', 'particulier')->count(),
            'entreprises' => $clients->where('type_client', 'entreprise')->count(),
            'new_in_period' => $newCount,
        ];
    }

    private function getProjetStats($isAdmin, $dateRange)
    {
        if (!$isAdmin) return null;

        $query = Projet::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return [
            'total' => $query->clone()->count(),
            'en_cours' => $query->clone()->where('statut_projet', 'en cours')->count(),
            'termine' => $query->clone()->where('statut_projet', 'terminé')->count(),
            'en_attente' => $query->clone()->where('statut_projet', 'en attente')->count(),
            'annule' => $query->clone()->where('statut_projet', 'annulé')->count(),
        ];
    }

    // ==================== CHARTS DATA (Modifiées) ====================

    private function getPointagesMonthlyChart($isAdmin, $user, $dateRange)
    {
        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        if ($dateRange['type'] === 'yearly' || $dateRange['type'] === 'all_time') {
            // Afficher les 12 derniers mois
            $data = [];
            $startMonth = $dateRange['type'] === 'yearly' 
                ? Carbon::createFromDate($dateRange['start']->year, 1, 1)
                : Carbon::now()->subMonths(11);

            for ($i = 0; $i < 12; $i++) {
                $date = $startMonth->copy()->addMonths($i);
                
                $monthQuery = $query->clone()
                    ->whereMonth('date_pointage', $date->month)
                    ->whereYear('date_pointage', $date->year);

                $total = $monthQuery->clone()->count();
                $retards = $monthQuery->clone()
                    ->whereNotNull('heure_arrivee')
                    ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
                    ->count();

                $data[] = [
                    'month' => $date->locale('fr')->format('M Y'),
                    'total' => $total,
                    'retards' => $retards,
                    'ponctuel' => $total - $retards,
                ];
            }
        } else {
            // Afficher par semaine pour le mois
            $data = [];
            for ($i = 0; $i < 4; $i++) {
                $startOfWeek = $dateRange['start']->copy()->addWeeks($i);
                $endOfWeek = $startOfWeek->copy()->endOfWeek();

                if ($endOfWeek->gt($dateRange['end'])) {
                    $endOfWeek = $dateRange['end'];
                }

                $weekQuery = $query->clone()
                    ->whereBetween('date_pointage', [$startOfWeek, $endOfWeek]);

                $total = $weekQuery->clone()->count();
                $retards = $weekQuery->clone()
                    ->whereNotNull('heure_arrivee')
                    ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
                    ->count();

                $data[] = [
                    'month' => 'Sem ' . ($i + 1),
                    'total' => $total,
                    'retards' => $retards,
                    'ponctuel' => $total - $retards,
                ];
            }
        }

        return $data;
    }

    private function getTachesStatusChart($isAdmin, $user, $dateRange)
    {
        $query = Tache::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('datedebut', '<=', Carbon::now());
        }

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return [
            'labels' => ['Nouveau', 'En cours', 'Terminé', 'En retard'],
            'data' => [
                $query->clone()->where('status', 'nouveau')->count(),
                $query->clone()->where('status', 'en cours')->count(),
                $query->clone()->where('status', 'termine')->count(),
                $query->clone()
                    ->where('status', '!=', 'termine')
                    ->whereNotNull('date_fin_prevue')
                    ->where('date_fin_prevue', '<', Carbon::now())
                    ->count(),
            ],
            'colors' => ['#17a2b8', '#ffc107', '#28a745', '#dc3545'],
        ];
    }

    private function getObjectifsProgressChart($isAdmin, $user, $dateRange)
    {
        $query = Objectif::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return [
            'labels' => ['0-25%', '26-50%', '51-75%', '76-99%', '100%'],
            'data' => [
                $query->clone()->where('progress', '>=', 0)->where('progress', '<=', 25)->count(),
                $query->clone()->where('progress', '>', 25)->where('progress', '<=', 50)->count(),
                $query->clone()->where('progress', '>', 50)->where('progress', '<=', 75)->count(),
                $query->clone()->where('progress', '>', 75)->where('progress', '<', 100)->count(),
                $query->clone()->where('progress', '>=', 100)->count(),
            ],
            'colors' => ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745'],
        ];
    }

    private function getProjetsStatusChart($isAdmin, $dateRange)
    {
        if (!$isAdmin) return null;

        $query = Projet::query();

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return [
            'labels' => ['En cours', 'Terminé', 'En attente', 'Annulé'],
            'data' => [
                $query->clone()->where('statut_projet', 'en cours')->count(),
                $query->clone()->where('statut_projet', 'terminé')->count(),
                $query->clone()->where('statut_projet', 'en attente')->count(),
                $query->clone()->where('statut_projet', 'annulé')->count(),
            ],
            'colors' => ['#ffc107', '#28a745', '#17a2b8', '#dc3545'],
        ];
    }

    private function getUsersPerformanceChart($isAdmin, $dateRange)
    {
        if (!$isAdmin) return null;

        $query = User::whereHas('taches', function ($q) use ($dateRange) {
            $q->where('status', 'termine');
            if ($dateRange['start']) {
                $q->whereBetween('updated_at', [$dateRange['start'], $dateRange['end']]);
            }
        });

        $users = $query->withCount(['taches as taches_terminees' => function ($q) use ($dateRange) {
            $q->where('status', 'termine');
            if ($dateRange['start']) {
                $q->whereBetween('updated_at', [$dateRange['start'], $dateRange['end']]);
            }
        }])->orderBy('taches_terminees', 'desc')
          ->limit(10)
          ->get();

        return [
            'labels' => $users->pluck('name')->toArray(),
            'data' => $users->pluck('taches_terminees')->toArray(),
        ];
    }

    private function getRetardsTrendChart($isAdmin, $user, $dateRange)
    {
        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        // Dernières 4 semaines
        $data = [];
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $retards = $query->clone()
                ->whereBetween('date_pointage', [$startOfWeek, $endOfWeek])
                ->whereNotNull('heure_arrivee')
                ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
                ->count();

            $data[] = [
                'week' => 'Semaine ' . (4 - $i),
                'retards' => $retards,
            ];
        }

        return $data;
    }

    // ==================== ACTIVITÉS RÉCENTES (Modifiées) ====================

    private function getRecentTaches($isAdmin, $user, $limit, $dateRange)
    {
        $query = Tache::with('users', 'creator');
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('datedebut', '<=', Carbon::now());
        }

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return $query->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    private function getRecentPointages($isAdmin, $user, $limit, $dateRange)
    {
        $query = SuivrePointage::with('user');
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        if ($dateRange['start']) {
            $query->whereBetween('date_pointage', [$dateRange['start'], $dateRange['end']]);
        }

        return $query->orderBy('date_pointage', 'desc')
                    ->orderBy('heure_arrivee', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getRecentProjets($isAdmin, $limit, $dateRange)
    {
        if (!$isAdmin) return null;

        $query = Projet::with('users');

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    private function getUpcomingRendezVous($isAdmin, $limit)
    {
        if (!$isAdmin) return null;

        return RendezVous::with('projet.users')
            ->where('date_heure', '>=', Carbon::now())
            ->where('statut', '!=', 'annulé')
            ->orderBy('date_heure', 'asc')
            ->limit($limit)
            ->get();
    }

    // ==================== TOP PERFORMERS (Modifié) ====================

    private function getTopPerformers($dateRange)
    {
        $query = User::whereHas('taches');

        return $query->withCount([
            'taches as taches_terminees' => function ($q) use ($dateRange) {
                $q->where('status', 'termine');
                if ($dateRange['start']) {
                    $q->whereBetween('updated_at', [$dateRange['start'], $dateRange['end']]);
                }
            },
            'suiviPointages as pointages_ponctuel' => function ($q) use ($dateRange) {
                if ($dateRange['start']) {
                    $q->whereBetween('date_pointage', [$dateRange['start'], $dateRange['end']]);
                }
                $q->whereNotNull('heure_arrivee')
                  ->whereRaw('TIME(heure_arrivee) <= ?', ['09:10:00']);
            }
        ])
        ->orderBy('taches_terminees', 'desc')
        ->limit(5)
        ->get();
    }

    // ==================== ALERTS (Modifiées) ====================

    private function getRetardsAlert($isAdmin, $user, $dateRange)
    {
        $query = SuivrePointage::with('user');
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        if ($dateRange['start']) {
            $query->whereBetween('date_pointage', [$dateRange['start'], $dateRange['end']]);
        } else {
            $query->whereMonth('date_pointage', Carbon::now()->month)
                  ->whereYear('date_pointage', Carbon::now()->year);
        }

        $usersWithRetards = $query->select('iduser', DB::raw('COUNT(*) as retards_count'))
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
            ->groupBy('iduser')
            ->having('retards_count', '>', 3)
            ->with('user')
            ->get();

        return $usersWithRetards;
    }

    private function getTachesOverdueAlert($isAdmin, $user, $dateRange)
    {
        $query = Tache::with('users');
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return $query->where('status', '!=', 'termine')
            ->whereNotNull('date_fin_prevue')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->orderBy('date_fin_prevue', 'asc')
            ->limit(10)
            ->get();
    }

    private function getObjectifsIncomplets($isAdmin, $user, $dateRange)
    {
        $query = Objectif::with('users');
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        if ($dateRange['start']) {
            $query->whereBetween('created_at', [$dateRange['start'], $dateRange['end']]);
        }

        return $query->where('progress', '<', 100)
            ->where('date', '<', Carbon::now())
            ->orderBy('date', 'asc')
            ->limit(10)
            ->get();
    }

    // ==================== API ENDPOINTS ====================

    public function getChartData(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');
        $type = $request->input('type');

        $filterType = $request->input('filter_type', 'all_time');
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        $dateRange = $this->getDateRange($filterType, $selectedMonth, $selectedYear);

        switch ($type) {
            case 'pointages_monthly':
                return response()->json($this->getPointagesMonthlyChart($isAdmin, $user, $dateRange));
            case 'taches_status':
                return response()->json($this->getTachesStatusChart($isAdmin, $user, $dateRange));
            case 'objectifs_progress':
                return response()->json($this->getObjectifsProgressChart($isAdmin, $user, $dateRange));
            case 'projets_status':
                return response()->json($this->getProjetsStatusChart($isAdmin, $dateRange));
            case 'users_performance':
                return response()->json($this->getUsersPerformanceChart($isAdmin, $dateRange));
            case 'retards_trend':
                return response()->json($this->getRetardsTrendChart($isAdmin, $user, $dateRange));
            default:
                return response()->json(['error' => 'Type de chart invalide'], 400);
        }
    }

    // Export dashboard data
   public function exportStats(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        $filterType = $request->input('filter_type', 'all_time');
        $selectedMonth = $request->input('month', now()->month);
        $selectedYear = $request->input('year', now()->year);
        $dateRange = $this->getDateRange($filterType, $selectedMonth, $selectedYear);

        $stats = [
            'users' => $this->getUserStats($isAdmin, $dateRange),
            'pointages' => $this->getPointageStats($isAdmin, $user, $dateRange),
            'taches' => $this->getTacheStats($isAdmin, $user, $dateRange),
            'objectifs' => $this->getObjectifStats($isAdmin, $user, $dateRange),
            'clients' => $this->getClientStats($isAdmin, $dateRange),
            'projets' => $this->getProjetStats($isAdmin, $dateRange),
        ];

        $filename = 'dashboard_stats_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['Statistiques du Dashboard', 'Valeur'], ';');
            fputcsv($file, [''], ';');

            foreach ($stats as $category => $data) {
                if ($data) {
                    fputcsv($file, [strtoupper($category)], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($file, [$key, $value], ';');
                    }
                    fputcsv($file, [''], ';');
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function clientDashboard()
    {
        $user = Auth::user();

        $projets = $user->projets()->get();
        $projetIds = $projets->pluck('id');

        $totalProjets = $projets->count();
        $projetsEnCours = $projets->where('statut_projet', 'en cours')->count();
        $projetsTermines = $projets->where('statut_projet', 'terminé')->count();
        $projetsEnAttente = $projets->where('statut_projet', 'en attente')->count();
        $projetsAnnules = $projets->where('statut_projet', 'annulé')->count();

        $chartData = [
            'labels' => ['En cours', 'Terminés', 'En attente', 'Annulés'],
            'data' => [$projetsEnCours, $projetsTermines, $projetsEnAttente, $projetsAnnules]
        ];

        $projetsRecents = $projets->sortByDesc('created_at')->take(5);

        $rendezVous = RendezVous::whereIn('projet_id', $projetIds)
                                ->where('date_heure', '>', now())
                                ->orderBy('date_heure', 'asc')
                                ->take(5)->get();

        $reclamations = Reclamation::where('iduser', $user->id)
                                   ->where('status', '!=', 'resolved')
                                   ->latest()
                                   ->take(5)->get();

        return view('client.dashboard', compact(
            'user',
            'totalProjets',
            'projetsEnCours',
            'projetsTermines',
            'projetsEnAttente',
            'projetsAnnules',
            'projetsRecents',
            'rendezVous',
            'reclamations',
            'chartData'
        ));
    }
}