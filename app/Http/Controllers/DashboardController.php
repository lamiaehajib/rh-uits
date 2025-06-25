<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\User;
use App\Models\Tache;
use App\Models\Project;
use App\Models\Formation;
use App\Models\Objectif;
use App\Models\VenteObjectif;
use App\Models\SuivrePointage;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the index page with the necessary data.
     *
     * @return \Illuminate\View\View
     */
    function __construct()
    {
        $this->middleware('check.clocked.in');
        $this->middleware('permission:project-list|tache-list|formation-list|formation-delete', ['only' => ['index']]);
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

        if ($user->hasRole('Admin') || $user->hasRole('Admin1')) { 
            $userCount = User::count();

            // Get advanced statistics
            $stats = Cache::remember($cacheKey . '_admin', 300, function () use ($dateFilter) {
                return $this->getAdvancedStats($dateFilter);
            });

            // Apply search and filters for Admin (these are likely just for the tables on the dashboard, not the charts)
            $tasks = Tache::with(['user'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%')
                                 ->orWhereHas('user', function ($q) use ($searchTerm) {
                                     $q->where('name', 'like', '%' . $searchTerm . '%');
                                 });
                })
                ->when($statusFilter, function ($query, $statusFilter) {
                    return $query->where('status', $statusFilter);
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $projects = Project::with(['users'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('titre', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('nomclient', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('ville', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'date_project');
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

            $objectifs = Objectif::with(['user'])
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $dashboards = Dashboard::paginate(10); // Assuming this is for a general dashboard view
        } else {
            // Non-Admin data filtering by user ID
            $stats = Cache::remember($cacheKey . '_user', 300, function () use ($user, $dateFilter) {
                return $this->getUserStats($user->id, $dateFilter);
            });

            $tasks = Tache::where('iduser', $user->id)
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

            $objectifs = Objectif::where('iduser', $user->id)
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $projects = Project::with('users')
                ->whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('titre', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('nomclient', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter, 'date_project');
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

        // Add pointage punctuality data for the chart (fetched directly for initial load)
        $pointagePunctualityChartData = $this->getPointagePunctualityChartData($user);

        // Add reclamations data for dashboard view
        $reclamations = $this->getReclamationsForDashboard($user);


        return view('dashboard.index', compact(
            'userCount', 'tasks', 'projects', 'formations', 'venteObjectifs',
            'objectifs', 'dashboards', 'stats', 'recentActivities', 'productivityMetrics',
            'pointagePunctualityChartData',
            'reclamations',
            'hasClockedInToday', // Pass this variable to the view
            'hasClockedOutToday' // Pass this variable to the view
        ));
    }

    /**
     * Handle user pointage (clock-in/clock-out).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function togglePointage(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Find today's pointage record for the user
        $pointage = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', $today)
            ->first();

        if ($pointage) {
            // User has already clocked in today
            if ($pointage->heure_depart === null) {
                // User needs to clock out
                $pointage->heure_depart = Carbon::now();
                $pointage->save();
                return redirect()->back()->with('success', 'Vous avez pointé votre départ avec succès !');
            } else {
                // User has already clocked out for today
                return redirect()->back()->with('info', 'Vous avez déjà pointé votre départ pour aujourd\'hui.');
            }
        } else {
            // User needs to clock in
            $newPointage = new SuivrePointage();
            $newPointage->iduser = $user->id;
            $newPointage->heure_arrivee = Carbon::now();
            $newPointage->date_pointage = $today; // Assuming you have a date_pointage column
            $newPointage->save();
            return redirect()->back()->with('success', 'Vous avez pointé votre arrivée avec succès !');
        }
    }


    /**
     * Get advanced statistics for admin dashboard
     */
    private function getAdvancedStats($dateFilter = null)
    {
        $query = function($model) use ($dateFilter) {
            $q = $model::query();
            if ($dateFilter) {
                $q = $this->applyDateFilter($q, $dateFilter);
            }
            return $q;
        };

        return [
            'total_tasks' => $query(Tache::class)->count(),
            'completed_tasks' => (clone $query(Tache::class))->where('status', 'terminé')->count(),
            'pending_tasks' => (clone $query(Tache::class))->where('status', 'en cours')->count(),
            'total_projects' => $query(Project::class)->count(),
            'active_projects' => (clone $query(Project::class))->whereNotNull('date_project')->count(),
            'total_formations' => $query(Formation::class)->count(),
            'completed_formations' => (clone $query(Formation::class))->where('status', 'terminé')->count(),
            'total_users' => User::count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'completion_rate' => $this->getCompletionRate($dateFilter),
            'productivity_score' => $this->getProductivityScore($dateFilter)
        ];
    }

    /**
     * Get user-specific statistics
     */
    private function getUserStats($userId, $dateFilter = null)
    {
        $queryForUser = function($model) use ($userId, $dateFilter) {
            // For projects and formations, the relation is Many-to-Many via `users`
            if ($model === Project::class || $model === Formation::class) {
                $q = $model::whereHas('users', function($q_inner) use ($userId) {
                    $q_inner->where('users.id', $userId);
                });
            } else {
                // For Tache, Objectif, VenteObjectif, it's directly by 'iduser'
                $q = $model::where('iduser', $userId);
            }

            if ($dateFilter) {
                // Apply date filter based on the model and appropriate column
                $dateColumn = 'created_at'; // Default column
                if ($model === Project::class) {
                    $dateColumn = 'date_project';
                } elseif ($model === Formation::class) {
                    $dateColumn = 'date';
                }
                $q = $this->applyDateFilter($q, $dateFilter, $dateColumn);
            }
            return $q;
        };

        return [
            'my_tasks' => $queryForUser(Tache::class)->count(),
            'completed_tasks' => (clone $queryForUser(Tache::class))->where('status', 'terminé')->count(),
            'pending_tasks' => (clone $queryForUser(Tache::class))->where('status', 'en cours')->count(), // Use clone
            'new_tasks' => (clone $queryForUser(Tache::class))->where('status', 'nouveau')->count(), // Use clone

            'my_projects' => $queryForUser(Project::class)->count(),
            'my_formations' => $queryForUser(Formation::class)->count(),
            'my_objectifs' => $queryForUser(Objectif::class)->count(),
            'completion_rate' => $this->getUserCompletionRate($userId, $dateFilter), // This calculation is based on completed tasks vs total tasks
            'productivity_score' => $this->getUserProductivityScore($userId, $dateFilter) // Call the correct method
        ];
    }

    /**
     * Apply date filter to query
     */
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

    /**
     * Get recent activities
     */
    private function getRecentActivities($user)
    {
        $activities = collect();

        // Recent tasks
        $recentTasks = Tache::where('iduser', $user->id)
            ->latest()
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

        // Recent projects
        $recentProjects = Project::whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->latest()
            ->limit(3)
            ->get()
            ->map(function($project) {
                return [
                    'type' => 'project',
                    'title' => $project->titre,
                    'status' => 'active', // Assuming projects have a simpler status here, or adapt if needed
                    'date' => $project->created_at,
                    'icon' => 'fas fa-project-diagram',
                    'color' => 'blue'
                ];
            });

        return $activities->merge($recentTasks)->merge($recentProjects)->sortByDesc('date')->take(8);
    }

    /**
     * Get productivity metrics
     */
    private function getProductivityMetrics($user)
    {
        $thisMonth = now()->month;
        $lastMonth = now()->subMonth()->month;

       $thisMonthTasks = Tache::where('iduser', $user->id)
            ->whereMonth('created_at', $thisMonth)
            ->count();

        $lastMonthTasks = Tache::where('iduser', $user->id)
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

    /**
     * Get completion rate
     */
    private function getCompletionRate($dateFilter = null)
    {
        $query = Tache::query();
        if ($dateFilter) {
            $query = $this->applyDateFilter($query, $dateFilter);
        }

        $total = $query->count();
        $completed = (clone $query)->where('status', 'terminé')->count(); // Use clone

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    /**
     * Get user completion rate
     */
    private function getUserCompletionRate($userId, $dateFilter = null)
    {
        $query = Tache::where('iduser', $userId);
        if ($dateFilter) {
            $query = $this->applyDateFilter($query, $dateFilter);
        }

        $total = $query->count();
        $completed = (clone $query)->where('status', 'terminé')->count(); // Use clone

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    /**
     * Get productivity score
     */
    private function getProductivityScore($dateFilter = null)
    {
        // Complex calculation based on tasks completed, time taken, etc.
        $completionRate = $this->getCompletionRate($dateFilter);
        $efficiency = 85; // This would be calculated based on actual metrics

        return round(($completionRate + $efficiency) / 2, 1);
    }

    /**
     * Get user productivity score
     */
    private function getUserProductivityScore($userId, $dateFilter = null) // Renamed this method
    {
        $completionRate = $this->getUserCompletionRate($userId, $dateFilter);
        $efficiency = $this->getEfficiencyScore($userId);

        return round(($completionRate + $efficiency) / 2, 1);
    }

    /**
     * Get status color
     */
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
                return 'yellow';
            case 'nouveau':
            case 'new':
                return 'gray';
            default:
                return 'gray';
        }
    }

    /**
     * Get average completion time
     */
    private function getAverageCompletionTime($userId)
    {
        // This would calculate based on task creation and completion dates
        return '2.5 jours'; // Placeholder
    }

    /**
     * Get efficiency score
     */
    private function getEfficiencyScore($userId)
    {
        // Complex calculation based on various factors
        return 78.5; // Placeholder
    }

    /**
     * Export dashboard data
     */
    public function export(Request $request)
    {
        // Implementation for exporting dashboard data to Excel/PDF
        // This would use packages like maatwebsite/excel or dompdf
    }

    /**
     * Get dashboard analytics API
     */
    public function analytics(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', 'month'); // Default to 'month'
        $punctualityPeriod = $request->get('punctuality_period', 'all'); // New parameter for punctuality chart period

        return response()->json([
            'tasks_chart' => $this->getTasksChartData($user, $period),
            'projects_chart' => $this->getProjectsChartData($user, $period),
            'productivity_chart' => $this->getProductivityChartData($user, $period),
            'pointage_chart' => $this->getPointageChartData($user, $period),
            'pointage_punctuality_chart' => $this->getPointagePunctualityChartData($user, $punctualityPeriod),
            'reclamations_dashboard' => $this->getReclamationsForDashboard($user)
        ]);
    }

    /**
     * Get tasks chart data
     */
    private function getTasksChartData($user, $period)
    {
        $query = Tache::query();

        if (!$user->hasRole('Admin') && !$user->hasRole('Admin1')) {
            $query->where('iduser', $user->id);
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


    /**
     * Get projects chart data (static data - consider making it dynamic)
     */
    private function getProjectsChartData($user, $period)
    {
        // This remains static for now, you might want to make it dynamic based on your Project model statuses
        return [
            'labels' => ['Active', 'Completed', 'On Hold'],
            'data' => [45, 32, 8]
        ];
    }

    /**
     * Get productivity chart data
     */
    private function getProductivityChartData($user, $period)
    {
        $labels = [];
        $productivityData = [];

        $now = Carbon::now();
        $startOfThisWeek = $now->copy()->startOfWeek(Carbon::MONDAY);

        for ($i = 3; $i >= 0; $i--) {
            $currentWeekStart = $startOfThisWeek->copy()->subWeeks($i);
            $currentWeekEnd = $currentWeekStart->copy()->endOfWeek(Carbon::SUNDAY);

            $query = Tache::where('status', 'terminé')
                            ->whereBetween('updated_at', [$currentWeekStart, $currentWeekEnd]);

            if (!$user->hasRole('Admin') && !$user->hasRole('Admin1')) {
                $query->where('iduser', $user->id);
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

    /**
     * Get pointage chart data (time worked per day/week/month)
     */
    private function getPointageChartData($user, $period)
    {
        $labels = [];
        $totalHoursData = [];

        $query = SuivrePointage::query();

        if (!$user->hasRole('Admin') && !$user->hasRole('Admin1')) {
            $query->where('iduser', $user->id);
        }

        $query->whereNotNull('heure_arrivee')
              ->whereNotNull('heure_depart');

        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dayTotalMinutes = (clone $query)
                        ->whereDate('heure_arrivee', $date->toDateString())
                        ->get()
                        ->sum(function($pointage) {
                            return Carbon::parse($pointage->heure_arrivee)->diffInMinutes(Carbon::parse($pointage->heure_depart));
                        });
                    $labels[] = $date->format('D d/m');
                    $totalHoursData[] = round($dayTotalMinutes / 60, 1);
                }
                break;
            case 'month':
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                $currentWeek = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
                $weekCount = 0;

                while ($currentWeek->lessThanOrEqualTo($endOfMonth) && $weekCount < 4) {
                    $weekEnd = $currentWeek->copy()->endOfWeek(Carbon::SUNDAY);
                    if ($weekEnd->greaterThan($endOfMonth)) {
                        $weekEnd = $endOfMonth;
                    }

                    $weekTotalMinutes = (clone $query)
                        ->whereBetween('heure_arrivee', [$currentWeek, $weekEnd])
                        ->get()
                        ->sum(function($pointage) {
                            return Carbon::parse($pointage->heure_arrivee)->diffInMinutes(Carbon::parse($pointage->heure_depart));
                        });

                    $labels[] = 'Sem. ' . $currentWeek->format('W') . ' (' . $currentWeek->format('d/m') . ')';
                    $totalHoursData[] = round($weekTotalMinutes / 60, 1);

                    $currentWeek->addWeek();
                    $weekCount++;
                }
                break;
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $monthTotalMinutes = (clone $query)
                        ->whereMonth('heure_arrivee', $month->month)
                        ->whereYear('heure_arrivee', $month->year)
                        ->get()
                        ->sum(function($pointage) {
                            return Carbon::parse($pointage->heure_arrivee)->diffInMinutes(Carbon::parse($pointage->heure_depart));
                        });
                    $labels[] = $month->format('M Y');
                    $totalHoursData[] = round($monthTotalMinutes / 60, 1);
                }
                break;
            default: // Default to last 4 weeks if no period or invalid period provided
                $now = Carbon::now();
                $startOfThisWeek = $now->copy()->startOfWeek(Carbon::MONDAY);

                for ($i = 3; $i >= 0; $i--) {
                    $currentWeekStart = $startOfThisWeek->copy()->subWeeks($i);
                    $currentWeekEnd = $currentWeekStart->copy()->endOfWeek(Carbon::SUNDAY);

                    $weekTotalMinutes = (clone $query)
                        ->whereBetween('heure_arrivee', [$currentWeekStart, $currentWeekEnd])
                        ->get()
                        ->sum(function($pointage) {
                            return Carbon::parse($pointage->heure_arrivee)->diffInMinutes(Carbon::parse($pointage->heure_depart));
                        });

                    $labels[] = 'Semaine du ' . $currentWeekStart->format('d/m');
                    $totalHoursData[] = round($weekTotalMinutes / 60, 1);
                }
                break;
        }

        return [
            'labels' => $labels,
            'data' => $totalHoursData,
            'title' => 'Temps Travaillé (Heures)'
        ];
    }


    /**
     * Get pointage punctuality chart data (late vs on-time arrivals)
     */
    private function getPointagePunctualityChartData($user, $period = 'all')
    {
        $query = SuivrePointage::query();

        if (!$user->hasRole('Admin') && !$user->hasRole('Admin1')) {
            $query->where('iduser', $user->id);
        }

        // Apply period filter if needed for punctuality analysis
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
            // 'all' means no date filter
        }


        // Only consider pointages that have an arrival time for this analysis
        $allPointagesWithArrival = (clone $query)->whereNotNull('heure_arrivee')->get();
        $totalArrivals = $allPointagesWithArrival->count();

        $lateArrivalsCount = 0;
        if ($totalArrivals > 0) {
            $lateArrivalsCount = $allPointagesWithArrival
                ->filter(function($pointage) {
                    $arriveeTime = Carbon::parse($pointage->heure_arrivee);
                    // Define the threshold for late arrival: 9:10 AM
                    $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                    // Check if arrival time is strictly greater than 9:10:00
                    return $arriveeTime->greaterThan($expectedArrivee);
                })
                ->count();
        }

        $onTimeArrivalsCount = $totalArrivals - $lateArrivalsCount;

        // Calculate percentages
        $percentageLate = $totalArrivals > 0 ? round(($lateArrivalsCount / $totalArrivals) * 100, 1) : 0;
        $percentageOnTime = $totalArrivals > 0 ? round(($onTimeArrivalsCount / $totalArrivals) * 100, 1) : 0;

        return [
            'labels' => ['En Retard', 'À l\'heure'],
            'data' => [$percentageLate, $percentageOnTime],
            'colors' => ['#D32F2F', '#4CAF50'], // Red for late, Green for on-time
            'total' => $totalArrivals
        ];
    }

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

        if ($user->hasRole('Admin') || $user->hasRole('Admin1')) {
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
}