<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\User;
use App\Notifications\TacheCreatedNotification;
use App\Notifications\TacheUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TacheController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tache-list|tache-create|tache-edit|tache-delete', ['only' => ['index','show','duplicate']]);
        $this->middleware('permission:tache-create', ['only' => ['create','store']]);
        $this->middleware('permission:tache-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:tache-delete', ['only' => ['destroy']]);
        $this->middleware('permission:tache-show', ['only' => ['show']]);
    }

    public function index(Request $request)
{
    $user = auth()->user();
    $search = $request->get('search');
    $status = $request->get('status');
    $date_filter = $request->get('date_filter');
    $user_filter = $request->get('user_filter');
    $sort_by = $request->get('sort_by', 'created_at');
    $sort_direction = $request->get('sort_direction', 'desc');

    $query = Tache::with('users');

    // THIS IS THE KEY CHANGE FOR TacheController@index
    // Apply datedebut filter only if the user is NOT an admin
    if (!$this->isAdmin($user)) {
        $query->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        $query->where('datedebut', '<=', Carbon::now()); // Apply for non-admins
    }
    // If it's an admin, no datedebut filter is applied here by default, which is what we want for total tasks.
    // If you had a line like `$query->where('datedebut', '<=', Carbon::now());` outside the `if (!$this->isAdmin($user))` block, remove it.


    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhere('titre', 'like', "%{$search}%")
              ->orWhereDate('date', 'like', "%{$search}%");
        });
    }

    if ($status && $status !== 'all') {
        $query->where('status', $status);
    }

    // Had l'logic dyal filter "overdue" khdamna bih b `date_fin_prevue`
    if ($date_filter) {
        switch ($date_filter) {
            case 'today':
                // For admin, this will filter all tasks today. For non-admin, it will filter their tasks today.
                $query->whereDate('datedebut', Carbon::today());
                break;
            case 'this_week':
                $query->whereBetween('datedebut', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('datedebut', Carbon::now()->month);
                break;
            case 'overdue':
                // Hada howa l'filter lli bghiti, katsta3mel date_fin_prevue
                $query->where('status', '!=', 'termine')
                      ->whereNotNull('date_fin_prevue')
                      ->where('date_fin_prevue', '<', Carbon::now());
                break;
            case 'future': // Add a future filter if needed for admin to see them explicitly
                 if ($this->isAdmin($user)) {
                     $query->where('datedebut', '>', Carbon::now());
                 }
                 break;
            // For other date filters, applyDateFilter should handle it.
            // Consider if applyDateFilter needs to be explicitly called here for non-admins to ensure datedebut is used.
            // The existing `->when($dateFilter, function ($query, $dateFilter) { return $this->applyDateFilter($query, $dateFilter); })` is fine IF `applyDateFilter` is called for `datedebut` for Taches.
            // Looking at `DashboardController::applyDateFilter`, it uses `created_at` by default.
            // So, for Taches, you might want to explicitly specify `datedebut` here in `TacheController`.

            // Example:
            // if ($date_filter && !in_array($date_filter, ['today', 'this_week', 'this_month', 'overdue', 'future'])) {
            //     $query->when($date_filter, function ($query, $dateFilter) {
            //         return $this->applyDateFilter($query, $dateFilter, 'datedebut'); // Make sure it applies to datedebut
            //     });
            // }

        }
    }


    if ($user_filter && $user_filter !== 'all' && $this->isAdmin($user)) {
        $query->whereHas('users', function ($q) use ($user_filter) {
            $q->where('user_id', $user_filter);
        });
    }

    if ($sort_by === 'priorite') {
        $query->orderByRaw("FIELD(priorite, 'élevé', 'moyen', 'faible') " . $sort_direction);
    } else {
        $query->orderBy($sort_by, $sort_direction);
    }

    $taches = $query->paginate(10)->appends($request->query());

    // Hna kan3aytou 3la getTaskStats bach njibou les comptes kamlin
    $stats = $this->getTaskStats($user);

    $users = $this->isAdmin($user) ? User::all() : collect();

    return view('taches.index', compact('taches', 'stats', 'users'));
}

    public function create()
    {
        $users = User::all();
        return view('taches.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:4000',
            'duree' => 'required|string|max:255',
            'datedebut' => 'required|date|after_or_equal:today',
            'status' => 'required|in:nouveau,en cours,termine',
            'date' => 'required|in:jour,semaine,mois', // Had "date" ma msta3mla walou f duree, y9dar tkoun mghayra 3la dakchi li baghi
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'priorite' => 'required|in:faible,moyen,élevé',
            'retour' => 'nullable|string|max:5000',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('user_ids');
            $data['created_by'] = auth()->id();

            // Calculate date_fin_prevue
            $startDate = Carbon::parse($request->input('datedebut'));
            $data['date_fin_prevue'] = $this->calculateExpectedEndDate($startDate, $request->input('duree'));

            $tache = Tache::create($data);

            $tache->users()->attach($request->input('user_ids'));

            foreach ($request->input('user_ids') as $userId) {
                $user = User::findOrFail($userId);
                $user->notify(new TacheCreatedNotification($tache));
            }

            DB::commit();

            return redirect()->route('taches.index')
                                ->with('success', 'Tâche créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error creating tache: " . $e->getMessage());
            return redirect()->back()
                                ->with('error', 'Erreur lors de la création de la tâche. Détails: ' . $e->getMessage())
                                ->withInput();
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $tache = Tache::with(['users', 'creator'])->findOrFail($id);

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        return view('taches.show', compact('tache'));
    }

    public function edit($id, Request $request) // Zidna Request $request hna
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id);

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        $users = User::all();
        // Passi ga3 les filter parameters l'edit view
        $filterParams = $request->only(['search', 'status', 'date_filter', 'user_filter', 'sort_by', 'sort_direction']);
        return view('taches.edit', compact('tache', 'users', 'filterParams')); // Zidna filterParams
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id);
        $oldStatus = $tache->status;

        if ($this->isAdmin($user)) {
            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'required|string|max:5000',
                'duree' => 'required|string|max:255',
                'datedebut' => 'required|date',
                'status' => 'required|in:nouveau,en cours,termine',
                'date' => 'required|in:jour,semaine,mois',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'priorite' => 'required|in:faible,moyen,élevé',
            ]);

            $data = $request->except('retour', 'user_ids');
            $data['updated_by'] = auth()->id();

            // Recalculate date_fin_prevue on update
            $startDate = Carbon::parse($request->input('datedebut'));
            $data['date_fin_prevue'] = $this->calculateExpectedEndDate($startDate, $request->input('duree'));

            $tache->update($data);

            $tache->users()->sync($request->input('user_ids'));

        } elseif ($user->hasAnyRole(['USER_MULTIMEDIA', 'USER_TRAINING', 'Sales_Admin', 'USER_TECH'])) {
            $request->validate([
                'status' => 'required|in:nouveau,en cours,termine',
                'retour' => 'nullable|string|max:5000',
            ]);

            $tache->update([
                'status' => $request->status,
                'retour' => $request->retour,
                'updated_by' => auth()->id(),
            ]);
        } else {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        if ($oldStatus !== $tache->status) {
            foreach ($tache->users as $assignedUser) {
                $assignedUser->notify(new TacheUpdatedNotification($tache));
            }
        }

        // Hna ghadi nst3emlou les filters li jbna men l'request
        $filterParams = $request->only(['search', 'status', 'date_filter', 'user_filter', 'sort_by', 'sort_direction']);
        return redirect()->route('taches.index', $filterParams) // Passi les parameters hna
                            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $tache = Tache::findOrFail($id);

        $tache->delete();
        return redirect()->route('taches.index')
                            ->with('success', 'Tâche supprimée avec succès.');
    }

    public function duplicate($id)
    {
        $user = auth()->user();
        $originalTache = Tache::with('users')->findOrFail($id);

        if (!$this->hasAccessToTask($user, $originalTache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        $newTache = $originalTache->replicate();
        $newTache->status = 'nouveau';
        $newTache->created_by = auth()->id();
        $newTache->description = $originalTache->description . ' (Copie)';
        $newTache->titre = $originalTache->titre . ' (Copie)';
        $newTache->priorite = $originalTache->priorite;
        $newTache->retour = null;
        // Important: Recalculate date_fin_prevue for the duplicated task
        $newTache->date_fin_prevue = $this->calculateExpectedEndDate(
            Carbon::parse($newTache->datedebut),
            $newTache->duree
        );
        $newTache->save();

        $newTache->users()->attach($originalTache->users->pluck('id'));

        return redirect()->route('taches.index')
                            ->with('success', 'Tâche dupliquée avec succès.');
    }

    public function markAsComplete($id)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id);

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->back()->with('error', 'Accès refusé pour marquer cette tâche comme terminée.');
        }

        $tache->update([
            'status' => 'termine',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Tâche marquée comme terminée avec succès !');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $stats = $this->getTaskStats($user);
        
        $overdueTasks = $this->getOverdueTasks($user);
        
        $recentTasks = $this->getRecentTasks($user);

        return view('taches.dashboard', compact('stats', 'overdueTasks', 'recentTasks'));
    }

    private function isAdmin($user)
    {
        return $user->hasRole(['Sup_Admin', 'Custom_Admin']);
    }

    private function hasAccessToTask($user, $tache)
    {
        return $this->isAdmin($user) || $tache->users->contains($user->id);
    }
    
    // Had L'fonction kat7eseb date_fin_prevue
    private function calculateExpectedEndDate(Carbon $startDate, string $duree): ?Carbon
    {
        $duration = strtolower($duree);
        $expectedEndDate = null;

        if (preg_match('/(\d+)\s*jour/i', $duration, $matches)) {
            $expectedEndDate = $startDate->copy()->addDays($matches[1]);
        } elseif (preg_match('/(\d+)\s*semaine/i', $duration, $matches)) {
            $expectedEndDate = $startDate->copy()->addWeeks($matches[1]);
        } elseif (preg_match('/(\d+)\s*mois/i', $duration, $matches)) {
            $expectedEndDate = $startDate->copy()->addMonths($matches[1]);
        } else {
            // Ila kan format ghalat, n9adro nlogiw l'erreur wla n3tiw default.
            // Ila bghitiha gha "1 jour" b default ila ma l9a walou, zidha.
            \Log::warning("Unknown duration format for tache: " . $duree);
        }

        return $expectedEndDate;
    }

    // Had L'fonction kat7eseb ga3 les stats, menhom overdue count
    private function getTaskStats($user)
{
    $baseQuery = Tache::query();

    // The condition for filtering by user_id
    if (!$this->isAdmin($user)) {
        $baseQuery->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
        // For non-admins, their 'total' tasks should also exclude future ones
        $baseQuery->where('datedebut', '<=', Carbon::now()); // <--- ADD/KEEP THIS FOR NON-ADMIN'S TOTAL STAT
    }
    // If it's an admin, no datedebut filter is applied to $baseQuery for 'total' count

    // Overdue count kanakhdouha direkt men database
    // This query is specific to overdue, so it will always have its own conditions
    $overdueQuery = (clone $baseQuery)->where('status', '!=', 'termine')
                                       ->whereNotNull('date_fin_prevue')
                                       ->where('date_fin_prevue', '<', Carbon::now());
    // If it's an admin, $baseQuery doesn't have datedebut filter, so this will be on ALL tasks
    // If it's a regular user, $baseQuery has datedebut filter, so this will be on their active tasks.

    // Calculate specific statuses
    $newTasks = (clone $baseQuery)->where('status', 'nouveau')->count();
    $inProgressTasks = (clone $baseQuery)->where('status', 'en cours')->count();
    $completedTasks = (clone $baseQuery)->where('status', 'termine')->count();


    return [
        // THIS IS THE KEY CHANGE FOR getTaskStats
        // For admin, $baseQuery doesn't have datedebut filter, so it's total.
        // For non-admin, $baseQuery has datedebut filter, so it's total of active tasks.
        'total' => (clone $baseQuery)->count(), 
        'nouveau' => $newTasks,
        'en_cours' => $inProgressTasks,
        'termine' => $completedTasks,
        'overdue' => $overdueQuery->count(), // Hada houwa l'nombre dyal les tâches en retard
    ];
}

    // Had L'fonction katjib les tâches li en retard bach ytbano f dashboard (top 5)
    private function getOverdueTasks($user)
    {
        $query = Tache::with('users')
                      ->where('status', '!=', 'termine')
                      ->whereNotNull('date_fin_prevue')
                      ->where('date_fin_prevue', '<', Carbon::now());

        if (!$this->isAdmin($user)) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query->orderBy('date_fin_prevue', 'asc')->limit(5)->get();
    }

    private function getRecentTasks($user)
    {
        $query = Tache::with('users')->orderBy('created_at', 'desc');

        if (!$this->isAdmin($user)) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            // Hna bqina m7tafdin b datedebut <= Carbon::now() bach les tâches lli mazal ma bdaouch ma ybanouch f recent tasks
            $query->where('datedebut', '<=', Carbon::now()); 
        }

        return $query->limit(5)->get();
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format', 'csv');

        $query = Tache::with('users');
        
        if (!$this->isAdmin($user)) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $query->where('datedebut', '<=', Carbon::now());
        }

        $taches = $query->get();

        if ($format === 'csv') {
            return $this->exportToCsv($taches);
        }

        return redirect()->back()->with('error', 'Format d\'export non supporté.');
    }

    private function exportToCsv($taches)
    {
        $filename = 'taches_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($taches) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Titre', 'Description', 'Durée', 'Date début', 'Date Fin Prévue', 'Statut', 'Priorité', 'Retour', 'Utilisateurs Assignés', 'Créé le']); // Updated header to include date_fin_prevue

            foreach ($taches as $tache) {
                $assignedUsers = $tache->users->pluck('name')->implode(', ');
                fputcsv($file, [
                    $tache->id,
                    $tache->titre,
                    $tache->description,
                    $tache->duree,
                    $tache->datedebut,
                    $tache->date_fin_prevue ? Carbon::parse($tache->date_fin_prevue)->format('d/m/Y') : '', // Add date_fin_prevue
                    $tache->status,
                    $tache->priorite,
                    $tache->retour,
                    $assignedUsers,
                    $tache->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}