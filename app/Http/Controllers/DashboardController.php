<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\Tache;
use App\Models\Projet;
use App\Models\Avancement;
use App\Models\Formation;
use App\Models\Objectif;
use App\Models\VenteObjectif;
use App\Models\SuivrePointage;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function __construct()
    {
        $this->middleware('check.clocked.in')->except('clientDashboard');
        
        $this->middleware('permission:Dashboard|project-list|tache-list|formation-list|formation-delete', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $userCount = 0;

        // Check if the user is currently clocked in for today
        $hasClockedInToday = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', Carbon::today())
            ->exists();

        // Check if the user has clocked out for today
        $hasClockedOutToday = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', Carbon::today())
            ->whereNotNull('heure_depart')
            ->exists();
            
        // Get the search term and filters
        $searchTerm = $request->get('search');
        $statusFilter = $request->get('status');
        $dateFilter = $request->get('date_filter');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Cache key for performance
        $cacheKey = "dashboard_stats_" . $user->id . "_" . md5($searchTerm . $statusFilter . $dateFilter);

        // Determine if the user is an Sup_Admin
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        if ($isAdmin) { 
            $userCount = User::count();

            // Get advanced statistics
            $stats = Cache::remember($cacheKey . '_admin', 300, function () use ($dateFilter) {
                return $this->getAdvancedStats($dateFilter);
            });

            // Apply search and filters for Sup_Admin
            $tasks = Tache::with(['users'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%')
                                 ->orWhereHas('users', function ($q) use ($searchTerm) {
                                     $q->where('name', 'like', '%' . $searchTerm . '%');
                                 });
                })
                ->when($statusFilter, function ($query, $statusFilter) {
                    return $query->where('status', $statusFilter);
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'datedebut');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            // MODIFICATION: Charger les projets avec avancements et rendez-vous
            $projects = Projet::with(['users', 'avancements', 'rendezVous'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('titre', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'date_debut');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $formations = Formation::with(['users'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('name', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('nomformateur', 'like', '%' . $searchTerm . '%');
                })
                ->when($statusFilter, function ($query, $statusFilter) {
                    return $query->where('status', $statusFilter);
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'date');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $venteObjectifs = VenteObjectif::with(['user'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $objectifs = Objectif::with(['users'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $dashboards = Dashboard::paginate(10);
        } else {
            // Non-Admin data filtering by user ID
            $stats = Cache::remember($cacheKey . '_user', 300, function () use ($user, $dateFilter) {
                return $this->getUserStats($user->id, $dateFilter);
            });

            $tasks = Tache::whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
                ->where('datedebut', '<=', Carbon::now())
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($statusFilter, function ($query, $statusFilter) {
                    return $query->where('status', $statusFilter);
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $objectifs = Objectif::whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            // MODIFICATION: Charger les projets avec avancements et rendez-vous pour utilisateur normal
            $projects = Projet::with(['users', 'avancements', 'rendezVous'])
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('titre', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'date_debut');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $formations = Formation::with('users')
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('name', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('nomformateur', 'like', '%' . $searchTerm . '%');
                })
                ->when($statusFilter, function ($query, $statusFilter) {
                    return $query->where('status', $statusFilter);
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'date');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $venteObjectifs = VenteObjectif::where('iduser', $user->id)
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $dashboards = Dashboard::with(['user', 'task', 'project', 'formation', 'venteObjectif', 'objectif'])
                ->where('iduser', $user->id)
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('task', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('project', 'like', '%' . $searchTerm . '%');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);
        }

        // Get recent activities
        $recentActivities = $this->getRecentActivities($user);

        // Get productivity metrics
        $productivityMetrics = $this->getProductivityMetrics($user);

        // Add pointage punctuality data for the chart
        $pointagePunctualityChartData = $this->getPointagePunctualityChartData($user);

        // Add reclamations data for dashboard view
        $reclamations = $this->getReclamationsForDashboard($user);

        return view('dashboard.index', compact(
            'userCount', 'tasks', 'projects', 'formations', 'venteObjectifs',
            'objectifs', 'dashboards', 'stats', 'recentActivities', 'productivityMetrics',
            'pointagePunctualityChartData',
            'reclamations',
            'hasClockedInToday',
            'hasClockedOutToday'
        ));
    }

    public function togglePointage(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        $userLatitude = $request->input('user_latitude', null);
        $userLongitude = $request->input('user_longitude', null);

        $pointage = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', $today)
            ->first();

        if ($pointage) {
            if ($pointage->heure_depart === null) {
                $pointage->heure_depart = Carbon::now();
                $pointage->longitude_depart = $userLongitude;
                $pointage->latitude_depart = $userLatitude;
                $pointage->save();
                return redirect()->back()->with('success', 'Vous avez pointé votre départ avec succès !');
            } else {
                return redirect()->back()->with('info', 'Vous avez déjà pointé votre départ pour aujourd\'hui.');
            }
        } else {
            $newPointage = new SuivrePointage();
            $newPointage->iduser = $user->id;
            $newPointage->heure_arrivee = Carbon::now();
            $newPointage->date_pointage = $today;
            $newPointage->longitude_arrivee = $userLongitude;
            $newPointage->latitude_arrivee = $userLatitude;
            $newPointage->save();
            return redirect()->back()->with('success', 'Vous avez pointé votre arrivée avec succès !');
        }
    }
    
    private function getAdvancedStats($dateFilter = null)
    {
        $query = function($model) use ($dateFilter) {
            $q = $model::query();
            if ($dateFilter) {
                $q = $this->applyDateFilter($q, $dateFilter);
            }
            return $q;
        };

        $totalTacheQuery = function() use ($dateFilter) {
            $q = Tache::query();
            if ($dateFilter) {
            }
            return $q;
        };

        return [
            'total_tasks' => $totalTacheQuery()->count(), 
            'completed_tasks' => (clone $query(Tache::class))->where('status', 'terminé')->count(),
            'pending_tasks' => (clone $query(Tache::class))->where('status', 'en cours')->count(),
            'total_projects' => $query(Projet::class)->count(),
            'active_projects' => (clone $query(Projet::class))->where('statut_projet', 'en cours')->count(),
            'total_formations' => $query(Formation::class)->count(),
            'completed_formations' => (clone $query(Formation::class))->where('status', 'terminé')->count(),
            'total_users' => User::count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'completion_rate' => $this->getCompletionRate($dateFilter),
            'productivity_score' => $this->getProductivityScore($dateFilter)
        ];
    }
    
    private function getUserStats($userId, $dateFilter = null)
    {
        $queryForUser = function($model) use ($userId, $dateFilter) {
            $q = $model::query();

            if (method_exists($model, 'users')) {
                $q->whereHas('users', function($q_inner) use ($userId) {
                    $q_inner->where('users.id', $userId);
                });
            } else {
                $q->where('iduser', $userId);
            }

            if ($dateFilter) {
                $dateColumn = 'created_at';
                if ($model === Projet::class) {
                    $dateColumn = 'date_debut';
                } elseif ($model === Formation::class) {
                    $dateColumn = 'date';
                } elseif ($model === Tache::class) {
                    $dateColumn = 'datedebut';
                }
                $q = $this->applyDateFilter($q, $dateFilter, $dateColumn);
            }

            if ($model === Tache::class) {
                $q->where('datedebut', '<=', Carbon::now());
            }

            return $q;
        };

        return [
            'my_tasks' => $queryForUser(Tache::class)->count(),
            'completed_tasks' => (clone $queryForUser(Tache::class))->where('status', 'terminé')->count(),
            'pending_tasks' => (clone $queryForUser(Tache::class))->where('status', 'en cours')->count(),
            'new_tasks' => (clone $queryForUser(Tache::class))->where('status', 'nouveau')->count(),
            'my_projects' => $queryForUser(Projet::class)->count(),
            'my_formations' => $queryForUser(Formation::class)->count(),
            'my_objectifs' => $queryForUser(Objectif::class)->count(),
            'completion_rate' => $this->getUserCompletionRate($userId, $dateFilter),
            'productivity_score' => $this->getUserProductivityScore($userId, $dateFilter)
        ];
    }
    
    private function applyDateFilter($query, $dateFilter, $dateColumn = 'created_at')
    {
        switch ($dateFilter) {
            case 'today':
                return $query->whereDate($dateColumn, today());
            case 'week':
                return $query->whereBetween($dateColumn, [now()->startOfWeek(), now()->endOfWeek()]);
            case 'month':
                return $query->whereMonth($dateColumn, now()->month)
                             ->whereYear($dateColumn, now()->year);
            case 'quarter':
                return $query->whereBetween($dateColumn, [now()->startOfQuarter(), now()->endOfQuarter()]);
            case 'year':
                return $query->whereYear($dateColumn, now()->year);
            default:
                return $query;
        }
    }
   
    private function getRecentActivities($user)
    {
        $activities = collect();
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        $taskQuery = Tache::query();
        if (!$isAdmin) {
            $taskQuery->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        $taskQuery->where('datedebut', '<=', Carbon::now()); 

        $recentTasks = $taskQuery->latest()
            ->limit(5)
            ->get()
            ->map(function($task) {
                return [
                    'type' => 'task',
                    'title' => $task->description,
                    'status' => $task->status,
                    'date' => $task->created_at,
                    'icon' => 'fas fa-tasks',
                    'color' => $this->getStatusColor($task->status)
                ];
            });

        $projectQuery = Projet::query();
        if (!$isAdmin) {
            $projectQuery->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        $recentProjects = $projectQuery->latest()
            ->limit(3)
            ->get()
            ->map(function($project) {
                return [
                    'type' => 'project',
                    'title' => $project->titre,
                    'status' => 'active',
                    'date' => $project->created_at,
                    'icon' => 'fas fa-project-diagram',
                    'color' => 'blue'
                ];
            });

        return $activities->merge($recentTasks)->merge($recentProjects)->sortByDesc('date')->take(8);
    }
   
    private function getProductivityMetrics($user)
    {
        $thisMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        $thisMonthTasksQuery = Tache::query();
        $lastMonthTasksQuery = Tache::query();

        if (!$isAdmin) {
            $thisMonthTasksQuery->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            $lastMonthTasksQuery->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        $thisMonthTasksQuery->where('datedebut', '<=', Carbon::now()); 
        $lastMonthTasksQuery->where('datedebut', '<=', Carbon::now());

        $thisMonthTasks = $thisMonthTasksQuery
            ->whereMonth('created_at', $thisMonth)
            ->count();

        $lastMonthTasks = $lastMonthTasksQuery
            ->whereMonth('created_at', $lastMonth)
            ->count();

        $tasksTrend = $lastMonthTasks > 0
            ? (($thisMonthTasks - $lastMonthTasks) / $lastMonthTasks) * 100
            : 0;

        return [
            'tasks_this_month' => $thisMonthTasks,
            'tasks_last_month' => $lastMonthTasks,
            'tasks_trend' => round($tasksTrend, 1),
            'avg_completion_time' => $this->getAverageCompletionTime($user->id),
            'efficiency_score' => $this->getEfficiencyScore($user->id)
        ];
    }
   
    private function getCompletionRate($dateFilter = null)
    {
        $query = Tache::query();
        if ($dateFilter) {
            $query = $this->applyDateFilter($query, $dateFilter);
        }
        $query->where('datedebut', '<=', Carbon::now());

        $total = $query->count();
        $completed = (clone $query)->where('status', 'terminé')->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
    
    private function getUserCompletionRate($userId, $dateFilter = null)
    {
        $query = Tache::whereHas('users', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
            ->where('datedebut', '<=', Carbon::now());
        if ($dateFilter) {
            $query = $this->applyDateFilter($query, $dateFilter);
        }

        $total = $query->count();
        $completed = (clone $query)->where('status', 'terminé')->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
    
    private function getProductivityScore($dateFilter = null)
    {
        $completionRate = $this->getCompletionRate($dateFilter);
        $efficiency = 85;

        return round(($completionRate + $efficiency) / 2, 1);
    }
    
    private function getUserProductivityScore($userId, $dateFilter = null)
    {
        $completionRate = $this->getUserCompletionRate($userId, $dateFilter);
        $efficiency = $this->getEfficiencyScore($userId);

        return round(($completionRate + $efficiency) / 2, 1);
    }
    
    private function getStatusColor($status)
    {
        switch (strtolower($status)) {
            case 'terminé':
            case 'completed':
                return 'green';
            case 'en cours':
            case 'active':
                return 'blue';
            case 'en attente':
            case 'pending':
            case 'nouveau':
            case 'new':
                return 'gray';
            default:
                return 'gray';
        }
    }
    
    private function getAverageCompletionTime($userId)
    {
        return '2.5 jours';
    }
    
    private function getEfficiencyScore($userId)
    {
        return 78.5;
    }
    
    public function export(Request $request)
    {
        // Implementation for exporting dashboard data
    }
    
    public function analytics(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', 'month');
        $punctualityPeriod = $request->get('punctuality_period', 'all');

        return response()->json([
            'tasks_chart' => $this->getTasksChartData($user, $period),
            'projects_chart' => $this->getProjectsChartData($user, $period),
            'productivity_chart' => $this->getProductivityChartData($user, $period),
            'pointage_chart' => $this->getPointageChartData($user, $period),
            'pointage_punctuality_chart' => $this->getPointagePunctualityChartData($user, $punctualityPeriod),
            'reclamations_dashboard' => $this->getReclamationsForDashboard($user)
        ]);
    }
    
    private function getTasksChartData($user, $period)
    {
        $query = Tache::query();
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        if (!$isAdmin) {
            $query->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        switch ($period) {
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }

        $newTasks = (clone $query)->where('status', 'nouveau')->count();
        $completedTasks = (clone $query)->where('status', 'terminé')->count();
        $inProgressTasks = (clone $query)->where('status', 'en cours')->count();

        return [
            'labels' => ['Nouveau', 'En Cours', 'Terminé'],
            'data' => [$newTasks, $inProgressTasks, $completedTasks],
            'colors' => [
                $this->getStatusColor('nouveau'),
                $this->getStatusColor('en cours'),
                $this->getStatusColor('terminé')
            ]
        ];
    }
 
    private function getProjectsChartData($user, $period)
    {
        return [
            'labels' => ['Active', 'Completed', 'On Hold'],
            'data' => [45, 32, 8]
        ];
    }
    
    private function getProductivityChartData($user, $period)
    {
        $labels = [];
        $productivityData = [];

        $now = Carbon::now();
        $startOfThisWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        for ($i = 3; $i >= 0; $i--) {
            $currentWeekStart = $startOfThisWeek->copy()->subWeeks($i);
            $currentWeekEnd = $currentWeekStart->copy()->endOfWeek(Carbon::SUNDAY);

            $query = Tache::where('status', 'terminé')
                            ->whereBetween('updated_at', [$currentWeekStart, $currentWeekEnd]);

            if (!$isAdmin) {
                $query->whereHas('users', function($q) use ($user) {
                    $q->where('users.id', $user->id);
                });
            }

            $count = $query->count();

            $labels[] = 'Semaine du ' . $currentWeekStart->format('d/m');
            $productivityData[] = $count;
        }

        return [
            'labels' => $labels,
            'productivity' => $productivityData
        ];
    }
   
 
    ## Get Pointage Punctuality Chart Data

    
    /**
     * Get pointage punctuality chart data (late vs on-time arrivals)
     */
    private function getPointagePunctualityChartData($user, $period = 'all')
{
    $query = SuivrePointage::query();

    if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) {
        $query->where('iduser', $user->id);
    }

    // Apply period filter - user i9der ichof 7ssab period
    switch ($period) {
        case 'today':
            $query->whereDate('heure_arrivee', today());
            break;
        case 'week':
            $query->whereBetween('heure_arrivee', [now()->startOfWeek(), now()->endOfWeek()]);
            break;
        case 'month':
            $query->whereMonth('heure_arrivee', now()->month)->whereYear('heure_arrivee', now()->year);
            break;
        case 'year':
            $query->whereYear('heure_arrivee', now()->year);
            break;
        // 'all' kaychof kolchi men bdat application
    }

    $allPointagesWithArrival = (clone $query)->whereNotNull('heure_arrivee')->get();
    $totalArrivals = $allPointagesWithArrival->count();

    $lateArrivalsCount = 0;
    if ($totalArrivals > 0) {
        $lateArrivalsCount = $allPointagesWithArrival
            ->filter(function($pointage) {
                $arriveeTime = Carbon::parse($pointage->heure_arrivee);
                $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                return $arriveeTime->greaterThan($expectedArrivee);
            })
            ->count();
    }

    $onTimeArrivalsCount = $totalArrivals - $lateArrivalsCount;

    $percentageLate = $totalArrivals > 0 ? round(($lateArrivalsCount / $totalArrivals) * 100, 1) : 0;
    $percentageOnTime = $totalArrivals > 0 ? round(($onTimeArrivalsCount / $totalArrivals) * 100, 1) : 0;

    return [
        'labels' => ['En Retard', 'À l\'heure'],
        'data' => [$percentageLate, $percentageOnTime],
        'colors' => ['#D32F2F', '#4CAF50'],
        'total' => $totalArrivals
    ];
}
   
 
    ## Get Reclamations Data for Dashboard

    
    /**
     * Get reclamations data for the dashboard based on user role.
     *
     * @param  \App\Models\User  $user
     * @param  string  $period
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getReclamationsForDashboard($user, $period = 'all')
    {
        $query = Reclamation::with('user')->latest();

        if ($user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin')) {
            // Admin sees unresolved (pending, in_progress, closed) reclamations
            $query->whereIn('status', ['pending', 'in_progress', 'closed']);
        } else {
            // Regular user sees their own resolved reclamations
            $query->where('iduser', $user->id)
                  ->where('status', 'resolved');
        }

        // Apply period filter if needed (e.g., to show only recent unresolved/resolved)
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            // 'all' means no date filter
        }

        return $query->take(5)->get(); // Limit to 5 for dashboard overview
    }


   // Final correct version for the clientDashboard method
public function clientDashboard()
{
    $user = Auth::user();

    // 1. Récupérer l'ID de tous les projets de l'utilisateur en une seule requête.
    // Cette ligne fonctionnera seulement si la relation 'projets' dans le modèle User est de type 'belongsToMany'.
    $projets = $user->projets()->get();
    $projetIds = $projets->pluck('id'); // Crée une collection des IDs de projets

    // 2. Utiliser les collections pour compter et filtrer les projets en mémoire.
    $totalProjets = $projets->count();
    $projetsEnCours = $projets->where('statut_projet', 'en cours')->count();
    $projetsTermines = $projets->where('statut_projet', 'terminé')->count();
    $projetsEnAttente = $projets->where('statut_projet', 'en attente')->count();
    $projetsAnnules = $projets->where('statut_projet', 'annulé')->count();

    // 3. Préparer les données pour le graphique.
    $chartData = [
        'labels' => ['En cours', 'Terminés', 'En attente', 'Annulés'],
        'data' => [$projetsEnCours, $projetsTermines, $projetsEnAttente, $projetsAnnules]
    ];

    // 4. Récupérer les 5 projets les plus récents de la collection.
    $projetsRecents = $projets->sortByDesc('created_at')->take(5);

    // 5. Récupérer les 5 prochains rendez-vous en se basant sur les IDs des projets.
    // On utilise `whereIn` pour vérifier si le `projet_id` est dans la liste des IDs de projets de l'utilisateur.
    $rendezVous = RendezVous::whereIn('projet_id', $projetIds)
                            ->where('date_heure', '>', now())
                            ->orderBy('date_heure', 'asc')
                            ->take(5)->get();

    // 6. Récupérer les 5 réclamations non résolues les plus récentes.
    $reclamations = Reclamation::where('iduser', $user->id) // Assurez-vous que cette colonne est correcte
                               ->where('status', '!=', 'resolved')
                               ->latest()
                               ->take(5)->get();

    // 7. Passer toutes les données à la vue.
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
