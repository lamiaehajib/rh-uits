<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\Tache;
use App\Models\Projet;
use App\Models\Avancement;

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
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        // Check pointage status
        $hasClockedInToday = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', Carbon::today())
            ->exists();

        $hasClockedOutToday = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', Carbon::today())
            ->whereNotNull('heure_depart')
            ->exists();

        // === SECTION 1: ÉQUIPES (Users) ===
        $equipeStats = $this->getEquipeStats($isAdmin);
        
        // === SECTION 2: POINTAGE ===
        $pointageStats = $this->getPointageStats($user, $isAdmin);
        
        // === SECTION 3: TÂCHES ===
        $tachesStats = $this->getTachesStats($user, $isAdmin, $request);
        
        // === SECTION 4: OBJECTIFS ===
        $objectifsStats = $this->getObjectifsStats($user, $isAdmin);
        
        // === SECTION 5: CLIENTS & PROJETS ===
        $clientsProjectsStats = $this->getClientsProjectsStats($isAdmin);

        // === DONNÉES GÉNÉRALES ===
        $searchTerm = $request->get('search');
        $statusFilter = $request->get('status');
        $dateFilter = $request->get('date_filter');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $cacheKey = "dashboard_stats_" . $user->id . "_" . md5($searchTerm . $statusFilter . $dateFilter);

        if ($isAdmin) { 
            $userCount = User::count();

            $stats = Cache::remember($cacheKey . '_admin', 300, function () use ($dateFilter) {
                return $this->getAdvancedStats($dateFilter);
            });

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
            $stats = Cache::remember($cacheKey . '_user', 300, function () use ($user, $dateFilter) {
                return $this->getUserStats($user->id, $dateFilter);
            });

            $tasks = Tache::whereHas('users', function ($q) use ($user) {
    $q->where('users.id', $user->id); // هنا نستخدم id الخاص بجدول المستخدمين
})
->where('datedebut', '<=', Carbon::now())
->when($searchTerm, function ($query, $searchTerm) {
    return $query->where('description', 'like', '%' . $searchTerm . '%');
})
->when($statusFilter, function ($query, $statusFilter) {
    return $query->where('status', $statusFilter);
})
->when($dateFilter, function ($query, $dateFilter) {
    // تأكد أن applyDateFilter لا تستخدم taches.iduser
    return $this->applyDateFilter($query, $dateFilter, 'datedebut'); 
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

            

            $venteObjectifs = VenteObjectif::where('iduser', $user->id)
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('description', 'like', '%' . $searchTerm . '%');
                })
                ->when($dateFilter, function ($query, $dateFilter) {
                    return $this->applyDateFilter($query, $dateFilter);
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);

            $dashboards = Dashboard::with(['user', 'task', 'project', 'venteObjectif', 'objectif'])
                ->where('iduser', $user->id)
                ->when($searchTerm, function ($query, $searchTerm) {
                    return $query->where('task', 'like', '%' . $searchTerm . '%')
                                 ->orWhere('project', 'like', '%' . $searchTerm . '%');
                })
                ->orderBy($sortBy, $sortOrder)
                ->paginate(10);
        }

        $recentActivities = $this->getRecentActivities($user);
        $productivityMetrics = $this->getProductivityMetrics($user);
        $pointagePunctualityChartData = $this->getPointagePunctualityChartData($user);
        $reclamations = $this->getReclamationsForDashboard($user);

        return view('dashboard.index', compact(
            'userCount', 'tasks', 'projects', 'venteObjectifs',
            'objectifs', 'dashboards', 'stats', 'recentActivities', 'productivityMetrics',
            'pointagePunctualityChartData',
            'reclamations',
            'hasClockedInToday',
            'hasClockedOutToday',
            'equipeStats',
            'pointageStats',
            'tachesStats',
            'objectifsStats',
            'clientsProjectsStats'
        ));
    }

    // === SECTION 1: ÉQUIPES ===
    private function getEquipeStats($isAdmin)
    {
        if (!$isAdmin) {
            return null;
        }

        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Users par rôle
        $usersByRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('roles.name', DB::raw('count(*) as total'))
            ->groupBy('roles.name')
            ->get();

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'new_users_this_month' => $newUsersThisMonth,
            'users_by_role' => $usersByRole,
            'chart_data' => [
                'labels' => $usersByRole->pluck('name')->toArray(),
                'data' => $usersByRole->pluck('total')->toArray()
            ]
        ];
    }

    // === SECTION 2: POINTAGE ===
    private function getPointageStats($user, $isAdmin)
    {
        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        // Stats du mois en cours
        $currentMonth = Carbon::now();
        $monthQuery = (clone $query)->whereMonth('heure_arrivee', $currentMonth->month)
                                     ->whereYear('heure_arrivee', $currentMonth->year);

        $totalPointages = $monthQuery->count();
        $pointagesComplets = (clone $monthQuery)->whereNotNull('heure_depart')->count();
        
        // Retards
        $retards = (clone $monthQuery)->whereNotNull('heure_arrivee')
            ->get()
            ->filter(function($pointage) {
                $arriveeTime = Carbon::parse($pointage->heure_arrivee);
                $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                return $arriveeTime->greaterThan($expectedArrivee);
            })
            ->count();

        // Départs anticipés
        $departsAnticipes = (clone $monthQuery)->whereNotNull('heure_depart')
            ->get()
            ->filter(function($pointage) {
                $departTime = Carbon::parse($pointage->heure_depart);
                $expectedDepart = Carbon::parse($departTime->format('Y-m-d') . ' 17:30:00');
                return $departTime->lessThan($expectedDepart);
            })
            ->count();

        // Utilisateurs les plus en retard (Admin uniquement)
        $topLateUsers = null;
if ($isAdmin) {
    $expectedTime = '09:10:00';
    
    $topLateUsers = SuivrePointage::select('iduser', DB::raw('count(*) as count'))
        ->whereMonth('heure_arrivee', $currentMonth->month)
        ->whereYear('heure_arrivee', $currentMonth->year)
        ->whereNotNull('heure_arrivee')
        // تحويل الفلترة إلى قاعدة البيانات باستخدام TIME()
        ->whereTime(DB::raw('TIME(heure_arrivee)'), '>', $expectedTime)
        // إضافة GROUP BY لحل خطأ SQL
        ->groupBy('iduser')
        ->orderByDesc('count')
        ->take(5)
        ->get()
        ->map(function($item) {
            return [
                'user' => User::find($item->iduser),
                'count' => $item->count
            ];
        });
}
        // Utilisateurs toujours à l'heure (Admin uniquement)
        $topPunctualUsers = null;
        if ($isAdmin) {
            $allUsers = User::where('is_active', true)->get();
            $topPunctualUsers = $allUsers->map(function($u) use ($currentMonth) {
                $userPointages = SuivrePointage::where('iduser', $u->id)
                    ->whereMonth('heure_arrivee', $currentMonth->month)
                    ->whereYear('heure_arrivee', $currentMonth->year)
                    ->whereNotNull('heure_arrivee')
                    ->get();

                if ($userPointages->isEmpty()) return null;

                $onTimeCount = $userPointages->filter(function($p) {
                    $arriveeTime = Carbon::parse($p->heure_arrivee);
                    $expectedArrivee = Carbon::parse($arriveeTime->format('Y-m-d') . ' 09:10:00');
                    return $arriveeTime->lessThanOrEqualTo($expectedArrivee);
                })->count();

                $percentage = ($onTimeCount / $userPointages->count()) * 100;

                return [
                    'user' => $u,
                    'percentage' => round($percentage, 1),
                    'total_pointages' => $userPointages->count()
                ];
            })
            ->filter()
            ->sortByDesc('percentage')
            ->take(5);
        }

        return [
            'total_pointages' => $totalPointages,
            'pointages_complets' => $pointagesComplets,
            'retards' => $retards,
            'departs_anticipes' => $departsAnticipes,
            'taux_ponctualite' => $totalPointages > 0 ? round((($totalPointages - $retards) / $totalPointages) * 100, 1) : 0,
            'top_late_users' => $topLateUsers,
            'top_punctual_users' => $topPunctualUsers
        ];
    }

    // === SECTION 3: TÂCHES ===
    private function getTachesStats($user, $isAdmin, $request)
    {
        $selectedMonth = $request->get('taches_month', Carbon::now()->format('Y-m'));
        $selectedUser = $request->get('taches_user');

        $query = Tache::query();

        if ($isAdmin && $selectedUser) {
            $query->whereHas('users', function($q) use ($selectedUser) {
                $q->where('users.id', $selectedUser);
            });
        } elseif (!$isAdmin) {
            $query->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        // Filtrer par mois
        if ($selectedMonth) {
            $date = Carbon::parse($selectedMonth);
            $query->whereMonth('datedebut', $date->month)
                  ->whereYear('datedebut', $date->year);
        }

        $totalTaches = $query->count();
        $tachesTerminees = (clone $query)->where('status', 'terminé')->count();
        $tachesEnCours = (clone $query)->where('status', 'en cours')->count();
        $tachesNouveau = (clone $query)->where('status', 'nouveau')->count();

        // Tâches en retard
        $tachesEnRetard = (clone $query)
            ->where('status', '!=', 'terminé')
            ->whereNotNull('date_fin_prevue')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->count();

        // Utilisateurs avec le plus de retards (Admin)
        $usersWithMostDelays = null;
       if ($isAdmin) {
    $usersWithMostDelays = User::whereHas('taches', function($q) {
            $q->where('status', '!=', 'terminé')
              ->whereNotNull('date_fin_prevue')
              ->where('date_fin_prevue', '<', Carbon::now());
        })
        ->withCount(['taches as delay_count' => function($q) {
            $q->where('status', '!=', 'terminé')
              ->whereNotNull('date_fin_prevue')
              ->where('date_fin_prevue', '<', Carbon::now());
        }])
        ->orderByDesc('delay_count')
        ->limit(5)
        ->get()
        ->map(function($user) {
            return [
                'user' => $user,
                'count' => $user->delay_count
            ];
        });

}

        // Utilisateurs qui terminent toujours à temps (Admin)
        $usersAlwaysOnTime = null;
        if ($isAdmin) {
            $allActiveUsers = User::where('is_active', true)->get();
            $usersAlwaysOnTime = $allActiveUsers->map(function($u) use ($selectedMonth) {
                $userTachesQuery = Tache::whereHas('users', function($q) use ($u) {
                    $q->where('users.id', $u->id);
                });

                if ($selectedMonth) {
                    $date = Carbon::parse($selectedMonth);
                    $userTachesQuery->whereMonth('datedebut', $date->month)
                                    ->whereYear('datedebut', $date->year);
                }

                $totalTaches = $userTachesQuery->count();
                if ($totalTaches === 0) return null;

                $tachesATemps = (clone $userTachesQuery)
                    ->where('status', 'terminé')
                    ->where(function($q) {
                        $q->whereNull('date_fin_prevue')
                          ->orWhereRaw('updated_at <= date_fin_prevue');
                    })
                    ->count();

                $percentage = ($tachesATemps / $totalTaches) * 100;

                return [
                    'user' => $u,
                    'percentage' => round($percentage, 1),
                    'total_taches' => $totalTaches
                ];
            })
            ->filter()
            ->sortByDesc('percentage')
            ->take(5);
        }

        $allUsers = $isAdmin ? User::where('is_active', true)->get() : collect();

        return [
            'total_taches' => $totalTaches,
            'taches_terminees' => $tachesTerminees,
            'taches_en_cours' => $tachesEnCours,
            'taches_nouveau' => $tachesNouveau,
            'taches_en_retard' => $tachesEnRetard,
            'taux_completion' => $totalTaches > 0 ? round(($tachesTerminees / $totalTaches) * 100, 1) : 0,
            'users_with_most_delays' => $usersWithMostDelays,
            'users_always_on_time' => $usersAlwaysOnTime,
            'all_users' => $allUsers,
            'selected_month' => $selectedMonth,
            'selected_user' => $selectedUser,
            'chart_data' => [
                'labels' => ['Nouveau', 'En Cours', 'Terminé', 'En Retard'],
                'data' => [$tachesNouveau, $tachesEnCours, $tachesTerminees, $tachesEnRetard]
            ]
        ];
    }

    // === SECTION 4: OBJECTIFS ===
    private function getObjectifsStats($user, $isAdmin)
    {
        $query = Objectif::query();

        if (!$isAdmin) {
            $query->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }

        $totalObjectifs = $query->count();
        $objectifsCompletes = (clone $query)->where('progress', '>=', 100)->count();
        $objectifsEnCours = (clone $query)->where('progress', '>', 0)->where('progress', '<', 100)->count();
        
        // Objectifs en retard
        $objectifsEnRetard = (clone $query)
            ->where('progress', '<', 100)
            ->where('date', '<', Carbon::now())
            ->count();

        return [
            'total_objectifs' => $totalObjectifs,
            'objectifs_completes' => $objectifsCompletes,
            'objectifs_en_cours' => $objectifsEnCours,
            'objectifs_en_retard' => $objectifsEnRetard,
            'taux_completion' => $totalObjectifs > 0 ? round(($objectifsCompletes / $totalObjectifs) * 100, 1) : 0,
            'chart_data' => [
                'labels' => ['Complétés', 'En Cours', 'En Retard'],
                'data' => [$objectifsCompletes, $objectifsEnCours, $objectifsEnRetard]
            ]
        ];
    }

    // === SECTION 5: CLIENTS & PROJETS ===
    private function getClientsProjectsStats($isAdmin)
    {
        if (!$isAdmin) {
            return null;
        }

        $totalClients = User::role('Client')->count();
        $clientsActifs = User::role('Client')->where('is_active', true)->count();

        $totalProjets = Projet::count();
        $projetsEnCours = Projet::where('statut_projet', 'en cours')->count();
        $projetsTermines = Projet::where('statut_projet', 'terminé')->count();
        $projetsEnAttente = Projet::where('statut_projet', 'en attente')->count();
        $projetsAnnules = Projet::where('statut_projet', 'annulé')->count();

        // Clients avec le plus de projets
        $topClients = DB::table('projet_user')
            ->join('users', 'projet_user.user_id', '=', 'users.id')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'Client')
            ->select('users.id', 'users.name', DB::raw('count(*) as projet_count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('projet_count')
            ->limit(5)
            ->get();

        // Rendez-vous à venir
        $prochainRendezVous = RendezVous::with('projet.users')
            ->where('date_heure', '>', Carbon::now())
            ->where('statut', '!=', 'annulé')
            ->orderBy('date_heure')
            ->limit(5)
            ->get();

        return [
            'total_clients' => $totalClients,
            'clients_actifs' => $clientsActifs,
            'total_projets' => $totalProjets,
            'projets_en_cours' => $projetsEnCours,
            'projets_termines' => $projetsTermines,
            'projets_en_attente' => $projetsEnAttente,
            'projets_annules' => $projetsAnnules,
            'top_clients' => $topClients,
            'prochain_rendez_vous' => $prochainRendezVous,
            'chart_data' => [
                'labels' => ['En Cours', 'Terminés', 'En Attente', 'Annulés'],
                'data' => [$projetsEnCours, $projetsTermines, $projetsEnAttente, $projetsAnnules]
            ]
        ];
    }

    // === MÉTHODES EXISTANTES (inchangées) ===
    
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
            return $q;
        };

        return [
            'total_tasks' => $totalTacheQuery()->count(), 
            'completed_tasks' => (clone $query(Tache::class))->where('status', 'terminé')->count(),
            'pending_tasks' => (clone $query(Tache::class))->where('status', 'en cours')->count(),
            'total_projects' => $query(Projet::class)->count(),
            'active_projects' => (clone $query(Projet::class))->where('statut_projet', 'en cours')->count(),
            
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
   
    private function getPointagePunctualityChartData($user, $period = 'all')
    {
        $query = SuivrePointage::query();

        if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) {
            $query->where('iduser', $user->id);
        }

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
   
    private function getReclamationsForDashboard($user, $period = 'all')
    {
        $query = Reclamation::with('user')->latest();

        if ($user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin')) {
            $query->whereIn('status', ['pending', 'in_progress', 'closed']);
        } else {
            $query->where('iduser', $user->id)
                  ->where('status', 'resolved');
        }

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
        }

        return $query->take(5)->get();
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