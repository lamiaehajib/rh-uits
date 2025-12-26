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

        // Statistiques générales
        $stats = [
            'users' => $this->getUserStats($isAdmin),
            'pointages' => $this->getPointageStats($isAdmin, $user),
            'taches' => $this->getTacheStats($isAdmin, $user),
            'objectifs' => $this->getObjectifStats($isAdmin, $user),
            'clients' => $this->getClientStats($isAdmin),
            'projets' => $this->getProjetStats($isAdmin),
        ];

        // Données pour les charts
        $chartData = [
            'pointages_monthly' => $this->getPointagesMonthlyChart($isAdmin, $user),
            'taches_status' => $this->getTachesStatusChart($isAdmin, $user),
            'objectifs_progress' => $this->getObjectifsProgressChart($isAdmin, $user),
            'projets_status' => $this->getProjetsStatusChart($isAdmin),
            'users_performance' => $this->getUsersPerformanceChart($isAdmin),
            'retards_trend' => $this->getRetardsTrendChart($isAdmin, $user),
        ];

        // Activités récentes
        $recentActivities = [
            'taches' => $this->getRecentTaches($isAdmin, $user, 5),
            'pointages' => $this->getRecentPointages($isAdmin, $user, 5),
            'projets' => $this->getRecentProjets($isAdmin, 5),
            'rendezvous' => $this->getUpcomingRendezVous($isAdmin, 5),
        ];

        // Top performers (pour admins seulement)
        $topPerformers = $isAdmin ? $this->getTopPerformers() : null;

        // Alerts et notifications
        $alerts = [
            'retards' => $this->getRetardsAlert($isAdmin, $user),
            'taches_overdue' => $this->getTachesOverdueAlert($isAdmin, $user),
            'objectifs_incomplets' => $this->getObjectifsIncomplets($isAdmin, $user),
        ];

        return view('dashboard.index', compact(
            'stats',
            'chartData',
            'recentActivities',
            'topPerformers',
            'alerts',
            'isAdmin'
        ));
    }

    // ==================== STATISTIQUES ====================

    private function getUserStats($isAdmin)
    {
        if (!$isAdmin) return null;

        return [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count(),
        ];
    }

    private function getPointageStats($isAdmin, $user)
    {
        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        $thisMonth = $query->clone()
            ->whereMonth('date_pointage', Carbon::now()->month)
            ->whereYear('date_pointage', Carbon::now()->year);

        $retards = $thisMonth->clone()
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
            ->count();

        $departsAnticipes = $thisMonth->clone()
            ->whereNotNull('heure_depart')
            ->whereRaw('TIME(heure_depart) < ?', ['17:30:00'])
            ->count();

        // Calculer temps moyen travaillé
        $pointagesTermines = $thisMonth->clone()->whereNotNull('heure_depart')->get();
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
            'total_this_month' => $thisMonth->count(),
            'retards' => $retards,
            'departs_anticipes' => $departsAnticipes,
            'temps_moyen_heures' => $tempsMoyen,
            'taux_ponctualite' => $thisMonth->count() > 0 
                ? round((($thisMonth->count() - $retards) / $thisMonth->count()) * 100, 2) 
                : 100,
        ];
    }

    private function getTacheStats($isAdmin, $user)
    {
        $query = Tache::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('datedebut', '<=', Carbon::now());
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

    private function getObjectifStats($isAdmin, $user)
    {
        $query = Objectif::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $total = $query->clone()->count();
        $completed = $query->clone()->where('progress', '>=', 100)->count();
        $inProgress = $query->clone()->where('progress', '>', 0)->where('progress', '<', 100)->count();
        
        // Objectifs en retard
        $overdue = $query->clone()
            ->where('progress', '<', 100)
            ->where('date', '<', Carbon::now())
            ->count();

        // Progression moyenne
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

    private function getClientStats($isAdmin)
    {
        if (!$isAdmin) return null;

        $clients = User::role('Client')->get();
        
        return [
            'total' => $clients->count(),
            'particuliers' => $clients->where('type_client', 'particulier')->count(),
            'entreprises' => $clients->where('type_client', 'entreprise')->count(),
            'new_this_month' => User::role('Client')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
        ];
    }

    private function getProjetStats($isAdmin)
    {
        if (!$isAdmin) return null;

        return [
            'total' => Projet::count(),
            'en_cours' => Projet::where('statut_projet', 'en cours')->count(),
            'termine' => Projet::where('statut_projet', 'terminé')->count(),
            'en_attente' => Projet::where('statut_projet', 'en attente')->count(),
            'annule' => Projet::where('statut_projet', 'annulé')->count(),
        ];
    }

    // ==================== CHARTS DATA ====================

    private function getPointagesMonthlyChart($isAdmin, $user)
    {
        $query = SuivrePointage::query();
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        // Derniers 6 mois
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
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

        return $data;
    }

    private function getTachesStatusChart($isAdmin, $user)
    {
        $query = Tache::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('datedebut', '<=', Carbon::now());
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

    private function getObjectifsProgressChart($isAdmin, $user)
    {
        $query = Objectif::query();
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
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

    private function getProjetsStatusChart($isAdmin)
    {
        if (!$isAdmin) return null;

        return [
            'labels' => ['En cours', 'Terminé', 'En attente', 'Annulé'],
            'data' => [
                Projet::where('statut_projet', 'en cours')->count(),
                Projet::where('statut_projet', 'terminé')->count(),
                Projet::where('statut_projet', 'en attente')->count(),
                Projet::where('statut_projet', 'annulé')->count(),
            ],
            'colors' => ['#ffc107', '#28a745', '#17a2b8', '#dc3545'],
        ];
    }

    private function getUsersPerformanceChart($isAdmin)
    {
        if (!$isAdmin) return null;

        // Top 10 users par nombre de tâches terminées ce mois
        $users = User::whereHas('taches', function ($q) {
            $q->where('status', 'termine')
              ->whereMonth('updated_at', Carbon::now()->month)
              ->whereYear('updated_at', Carbon::now()->year);
        })->withCount(['taches as taches_terminees' => function ($q) {
            $q->where('status', 'termine')
              ->whereMonth('updated_at', Carbon::now()->month)
              ->whereYear('updated_at', Carbon::now()->year);
        }])->orderBy('taches_terminees', 'desc')
          ->limit(10)
          ->get();

        return [
            'labels' => $users->pluck('name')->toArray(),
            'data' => $users->pluck('taches_terminees')->toArray(),
        ];
    }

    private function getRetardsTrendChart($isAdmin, $user)
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

    // ==================== ACTIVITÉS RÉCENTES ====================

    private function getRecentTaches($isAdmin, $user, $limit)
    {
        $query = Tache::with('users', 'creator');
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('datedebut', '<=', Carbon::now());
        }

        return $query->orderBy('created_at', 'desc')->limit($limit)->get();
    }

    private function getRecentPointages($isAdmin, $user, $limit)
    {
        $query = SuivrePointage::with('user');
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        return $query->orderBy('date_pointage', 'desc')
                    ->orderBy('heure_arrivee', 'desc')
                    ->limit($limit)
                    ->get();
    }

    private function getRecentProjets($isAdmin, $limit)
    {
        if (!$isAdmin) return null;

        return Projet::with('users')
            ->orderBy('created_at', 'desc')
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

    // ==================== TOP PERFORMERS ====================

    private function getTopPerformers()
    {
        return User::whereHas('taches')
            ->withCount([
                'taches as taches_terminees' => function ($q) {
                    $q->where('status', 'termine')
                      ->whereMonth('updated_at', Carbon::now()->month);
                },
                'suiviPointages as pointages_ponctuel' => function ($q) {
                    $q->whereMonth('date_pointage', Carbon::now()->month)
                      ->whereNotNull('heure_arrivee')
                      ->whereRaw('TIME(heure_arrivee) <= ?', ['09:10:00']);
                }
            ])
            ->orderBy('taches_terminees', 'desc')
            ->limit(5)
            ->get();
    }

    // ==================== ALERTS ====================

    private function getRetardsAlert($isAdmin, $user)
    {
        $query = SuivrePointage::with('user');
        
        if (!$isAdmin) {
            $query->where('iduser', $user->id);
        }

        // Utilisateurs avec plus de 3 retards ce mois
        $usersWithRetards = $query->select('iduser', DB::raw('COUNT(*) as retards_count'))
            ->whereMonth('date_pointage', Carbon::now()->month)
            ->whereYear('date_pointage', Carbon::now()->year)
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', ['09:10:00'])
            ->groupBy('iduser')
            ->having('retards_count', '>', 3)
            ->with('user')
            ->get();

        return $usersWithRetards;
    }

    private function getTachesOverdueAlert($isAdmin, $user)
    {
        $query = Tache::with('users');
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query->where('status', '!=', 'termine')
            ->whereNotNull('date_fin_prevue')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->orderBy('date_fin_prevue', 'asc')
            ->limit(10)
            ->get();
    }

    private function getObjectifsIncomplets($isAdmin, $user)
    {
        $query = Objectif::with('users');
        
        if (!$isAdmin) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
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

        switch ($type) {
            case 'pointages_monthly':
                return response()->json($this->getPointagesMonthlyChart($isAdmin, $user));
            case 'taches_status':
                return response()->json($this->getTachesStatusChart($isAdmin, $user));
            case 'objectifs_progress':
                return response()->json($this->getObjectifsProgressChart($isAdmin, $user));
            case 'projets_status':
                return response()->json($this->getProjetsStatusChart($isAdmin));
            case 'users_performance':
                return response()->json($this->getUsersPerformanceChart($isAdmin));
            case 'retards_trend':
                return response()->json($this->getRetardsTrendChart($isAdmin, $user));
            default:
                return response()->json(['error' => 'Type de chart invalide'], 400);
        }
    }

    // Export dashboard data
    public function exportStats(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');

        $stats = [
            'users' => $this->getUserStats($isAdmin),
            'pointages' => $this->getPointageStats($isAdmin, $user),
            'taches' => $this->getTacheStats($isAdmin, $user),
            'objectifs' => $this->getObjectifStats($isAdmin, $user),
            'clients' => $this->getClientStats($isAdmin),
            'projets' => $this->getProjetStats($isAdmin),
        ];

        $filename = 'dashboard_stats_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
            
            fputcsv($file, ['Statistiques du Dashboard', 'Valeur'], ';');
            fputcsv($file, [''], ';'); // Ligne vide

            foreach ($stats as $category => $data) {
                if ($data) {
                    fputcsv($file, [strtoupper($category)], ';');
                    foreach ($data as $key => $value) {
                        fputcsv($file, [$key, $value], ';');
                    }
                    fputcsv($file, [''], ';'); // Ligne vide
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