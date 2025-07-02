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

        $query = Tache::with('users'); // Eager load the 'users' relationship

        if (!$this->isAdmin($user)) {
            // If not an admin, only show tasks assigned to the current user
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $query->where('datedebut', '<=', Carbon::now());
        }

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

        if ($date_filter) {
            switch ($date_filter) {
                case 'today':
                    $query->whereDate('datedebut', Carbon::today());
                    break;
                case 'this_week':
                    $query->whereBetween('datedebut', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('datedebut', Carbon::now()->month);
                    break;
                case 'overdue':
                    $query->where('datedebut', '<', Carbon::now())
                                ->where('status', '!=', 'termine');
                    break;
            }
        }

        // User filter (for admins)
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
            'date' => 'required|in:jour,semaine,mois',
            'user_ids' => 'required|array', // Validate that it's an array of user IDs
            'user_ids.*' => 'exists:users,id', // Validate each ID exists in the users table
            'priorite' => 'required|in:faible,moyen,élevé',
            'retour' => 'nullable|string|max:5000',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('user_ids'); // Exclude user_ids from direct mass assignment
            $data['created_by'] = auth()->id();

            $tache = Tache::create($data);

            // Attach multiple users to the task
            $tache->users()->attach($request->input('user_ids'));

            // Send notification to each assigned user
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
        $tache = Tache::with(['users', 'creator'])->findOrFail($id); // Eager load 'users'

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        return view('taches.show', compact('tache'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id); // Eager load 'users'

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        $users = User::all();
        return view('taches.edit', compact('tache', 'users'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id); // Eager load 'users' to check assigned users
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

            $tache->update($data);

            // Sync the users for the task (detach existing and attach new ones)
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
            // Notify all assigned users when status changes
            foreach ($tache->users as $assignedUser) {
                $assignedUser->notify(new TacheUpdatedNotification($tache));
            }
        }

        return redirect()->route('taches.index')
                            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $tache = Tache::findOrFail($id);

        $tache->delete(); // This will also delete entries in the pivot table due to cascade delete
        return redirect()->route('taches.index')
                            ->with('success', 'Tâche supprimée avec succès.');
    }

    public function duplicate($id)
    {
        $user = auth()->user();
        $originalTache = Tache::with('users')->findOrFail($id); // Load users to duplicate them

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
        $newTache->save();

        // Duplicate the assigned users
        $newTache->users()->attach($originalTache->users->pluck('id'));

        return redirect()->route('taches.index')
                            ->with('success', 'Tâche dupliquée avec succès.');
    }

    public function markAsComplete($id)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id); // Load users to check access correctly

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
        // An admin always has access.
        // A regular user has access if they are one of the assigned users for the task.
        return $this->isAdmin($user) || $tache->users->contains($user->id);
    }
    
    private function getTaskStats($user)
    {
        $baseQuery = Tache::query(); 
        
        if (!$this->isAdmin($user)) {
            $baseQuery->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $baseQuery->where('datedebut', '<=', Carbon::now()); 
        }

        $notCompletedTasks = (clone $baseQuery)->where('status', '!=', 'termine');

        $overdueCount = 0;
        foreach ($notCompletedTasks->get() as $tache) {
            try {
                $duration = $tache->duree;
                $startDate = Carbon::parse($tache->datedebut);

                $expectedEndDate = null;
                if (preg_match('/(\d+)\s*jour/i', $duration, $matches)) {
                    $expectedEndDate = $startDate->addDays($matches[1]);
                } elseif (preg_match('/(\d+)\s*semaine/i', $duration, $matches)) {
                    $expectedEndDate = $startDate->addWeeks($matches[1]);
                } elseif (preg_match('/(\d+)\s*mois/i', $duration, $matches)) {
                    $expectedEndDate = $startDate->addMonths($matches[1]);
                } else {
                    $expectedEndDate = $startDate->addDay(); 
                }

                if ($expectedEndDate && $expectedEndDate->isPast()) {
                    $overdueCount++;
                }
            } catch (\Exception $e) {
                \Log::error("Error parsing task duration for tache ID: {$tache->id}. Error: {$e->getMessage()}");
            }
        }

        return [
            'total' => (clone $baseQuery)->count(), 
            'nouveau' => (clone $baseQuery)->where('status', 'nouveau')->count(),
            'en_cours' => (clone $baseQuery)->where('status', 'en cours')->count(),
            'termine' => (clone $baseQuery)->where('status', 'termine')->count(),
            'overdue' => $overdueCount,
        ];
    }

    private function getOverdueTasks($user)
    {
        $query = Tache::with('users') // Load 'users'
                      ->where('datedebut', '<', Carbon::now())
                      ->where('status', '!=', 'termine');

        if (!$this->isAdmin($user)) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query->orderBy('datedebut', 'asc')->limit(5)->get();
    }

    private function getRecentTasks($user)
    {
        $query = Tache::with('users')->orderBy('created_at', 'desc'); // Load 'users'

        if (!$this->isAdmin($user)) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            $query->where('datedebut', '<=', Carbon::now());
        }

        return $query->limit(5)->get();
    }

    public function export(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format', 'csv');

        $query = Tache::with('users'); // Load 'users'
        
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
            fputcsv($file, ['ID', 'Titre', 'Description', 'Durée', 'Date début', 'Statut', 'Priorité', 'Retour', 'Utilisateurs Assignés', 'Créé le']); // Updated header

            foreach ($taches as $tache) {
                // Get assigned user names, comma-separated
                $assignedUsers = $tache->users->pluck('name')->implode(', ');
                fputcsv($file, [
                    $tache->id,
                    $tache->titre,
                    $tache->description,
                    $tache->duree,
                    $tache->datedebut,
                    $tache->status,
                    $tache->priorite,
                    $tache->retour,
                    $assignedUsers, // Use the comma-separated names
                    $tache->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}