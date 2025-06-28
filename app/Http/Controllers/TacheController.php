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

        // Build the query
        $query = Tache::with('user');

        // Only show tasks where datedebut is today or in the past, UNLESS it's an admin viewing all tasks
        if (!$this->isAdmin($user)) {
            $query->where('datedebut', '<=', Carbon::now());
        }

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereDate('date', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Date filter
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
            $query->where('iduser', $user_filter);
        }

        // Sorting
        if ($sort_by === 'priority') {
            $query->orderByRaw("FIELD(status, 'en cours', 'nouveau', 'termine')");
        } else {
            $query->orderBy($sort_by, $sort_direction);
        }

        // Role-based access
        if (!$this->isAdmin($user)) {
            $query->where('iduser', $user->id);
        }

        $taches = $query->paginate(10)->appends($request->query());

        // Get statistics
        $stats = $this->getTaskStats($user);

        // Get users for filter (only for admins)
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
            'description' => 'required|string|max:2000',
            'duree' => 'required|string|max:255',
            'datedebut' => 'required|date|after_or_equal:today',
            'status' => 'required|in:nouveau,en cours,termine',
            'date' => 'required|in:jour,semaine,mois',
            'iduser' => 'required|exists:users,id',
            
            
        ]);

        try {
            DB::beginTransaction();

            // Add creator info
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $data['priority'] = $request->get('priority', 'medium');

            $tache = Tache::create($data);

            // Send notification
            $user = User::findOrFail($request->iduser);
            $user->notify(new TacheCreatedNotification($tache));

            DB::commit();

            return redirect()->route('taches.index')
                                 ->with('success', 'Tâche créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the actual error for debugging
            \Log::error("Error creating tache: " . $e->getMessage());
            return redirect()->back()
                                 ->with('error', 'Erreur lors de la création de la tâche. Détails: ' . $e->getMessage()) // Provide more detail in development
                                 ->withInput();
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $tache = Tache::with(['user', 'creator'])->findOrFail($id);

        // Check access
        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                 ->with('error', 'Accès refusé.');
        }

        return view('taches.show', compact('tache'));
    }

    public function edit($id)
    {
        $user = auth()->user();
        $tache = Tache::findOrFail($id);

        // Check access
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
        $tache = Tache::findOrFail($id);
        $oldStatus = $tache->status;

        if ($this->isAdmin($user)) {
            // Admin can modify everything
            $request->validate([
                'description' => 'required|string|max:4000',

                'duree' => 'required|string|max:255',
                'datedebut' => 'required|date',
                'status' => 'required|in:nouveau,en cours,termine',
                'date' => 'required|in:jour,semaine,mois',
                'iduser' => 'required|exists:users,id',
                'priority' => 'nullable|in:low,medium,high',
                'notes' => 'nullable|string|max:1000',
            ]);

            $data = $request->all();
            $data['updated_by'] = auth()->id();
            
            $tache->update($data);
        } elseif ($user->hasRole('UITS')) {
            // UITS can only modify status and add notes
            $request->validate([
                'status' => 'required|in:nouveau,en cours,termine',
                'notes' => 'nullable|string|max:1000',
            ]);

            $tache->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'updated_by' => auth()->id(),
            ]);
        } else {
            return redirect()->route('taches.index')
                                 ->with('error', 'Accès refusé.');
        }

        // Send notification if status changed
        if ($oldStatus !== $tache->status) {
            $assignedUser = User::find($tache->iduser);
            if ($assignedUser) {
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

    
    $tache->delete();
    return redirect()->route('taches.index')
                         ->with('success', 'Tâche supprimée avec succès.');
}

    // Nouvelle méthode pour dupliquer une tâche
    public function duplicate($id)
    {
        $user = auth()->user();
        $originalTache = Tache::findOrFail($id);

        if (!$this->hasAccessToTask($user, $originalTache)) {
            return redirect()->route('taches.index')
                                 ->with('error', 'Accès refusé.');
        }

        $newTache = $originalTache->replicate();
        $newTache->status = 'nouveau';
        $newTache->created_by = auth()->id();
        $newTache->description = $originalTache->description . ' (Copie)';
        $newTache->save();

        return redirect()->route('taches.index')
                             ->with('success', 'Tâche dupliquée avec succès.');
    }

    // Méthode pour marquer une tâche comme terminée rapidement
    public function markAsComplete($id)
    {
        $user = auth()->user();
        $tache = Tache::findOrFail($id);

        if (!$this->hasAccessToTask($user, $tache)) {
            // Change from JSON response to redirect with error
            return redirect()->back()->with('error', 'Accès refusé pour marquer cette tâche comme terminée.');
        }

        $tache->update([
            'status' => 'termine',
            'updated_by' => auth()->id(),
        ]);

        // Change from JSON response to redirect with success
        return redirect()->back()->with('success', 'Tâche marquée comme terminée avec succès !');
    }

    // Méthode pour obtenir les statistiques
    public function dashboard()
    {
        $user = auth()->user();
        $stats = $this->getTaskStats($user);
        
        // Tâches en retard
        $overdueTasks = $this->getOverdueTasks($user);
        
        // Tâches récentes
        $recentTasks = $this->getRecentTasks($user);

        return view('taches.dashboard', compact('stats', 'overdueTasks', 'recentTasks'));
    }

    // Helper methods
    private function isAdmin($user)
    {
        return $user->hasRole(['Sup_Admin', 'Custom_Admin']);
    }

    private function hasAccessToTask($user, $tache)
    {
        return $this->isAdmin($user) || $tache->iduser == $user->id;
    }

    
    ## Modified `getTaskStats` Method

   private function getTaskStats($user)
    {
        $baseQuery = Tache::query(); 
        
        if (!$this->isAdmin($user)) {
            $baseQuery->where('iduser', $user->id);
            // --- NEW ADDITION START for non-admin users ---
            // Only count tasks whose datedebut has arrived for regular users
            $baseQuery->where('datedebut', '<=', Carbon::now()); 
            // --- NEW ADDITION END ---
        }
        // --- NEW ADDITION START for admin users ---
        // If an admin is viewing these stats, you might still want to count ALL tasks,
        // or you might want them to also respect datedebut for 'current' stats.
        // I'll assume for admins, you want ALL tasks counted, unless a specific filter is applied later.
        // If you want admin stats to also respect datedebut, move the line above outside the if.
        // For now, it respects datedebut only for non-admins as requested implicitly.
        // If you want datedebut to apply to ALL stats regardless of role, put the line below here:
        // $baseQuery->where('datedebut', '<=', Carbon::now()); 
        // --- NEW ADDITION END ---

        // We need to fetch tasks that are not 'termine' first, then filter by calculated end date
        $notCompletedTasks = (clone $baseQuery)->where('status', '!=', 'termine');

        // Calculate overdue count based on 'datedebut' + 'duree'
        $overdueCount = 0;
        foreach ($notCompletedTasks->get() as $tache) { // Fetch results to iterate
            try {
                // Assuming 'duree' is a string like "1 jour", "3 jours", "2 semaines", "1 mois"
                // You might need a more robust parsing for 'duree' if it's more complex.
                // For simplicity, let's assume 'duree' values are like 'X jours', 'X semaines', 'X mois'.
                $duration = $tache->duree; // e.g., "1 jour", "2 semaines", "3 mois"
                $startDate = Carbon::parse($tache->datedebut); // Parse the start date

                $expectedEndDate = null;
                if (preg_match('/(\d+)\s*jour/i', $duration, $matches)) {
                    $expectedEndDate = $startDate->addDays($matches[1]);
                } elseif (preg_match('/(\d+)\s*semaine/i', $duration, $matches)) {
                    $expectedEndDate = $startDate->addWeeks($matches[1]);
                } elseif (preg_match('/(\d+)\s*mois/i', $duration, $matches)) {
                    $expectedEndDate = $startDate->addMonths($matches[1]);
                } else {
                    // Fallback if 'duree' format is unexpected, maybe treat as 1 day or log an error
                    $expectedEndDate = $startDate->addDay(); 
                }

                if ($expectedEndDate && $expectedEndDate->isPast()) {
                    $overdueCount++;
                }
            } catch (\Exception $e) {
                // Handle parsing errors for 'duree' or 'datedebut'
                \Log::error("Error parsing task duration for tache ID: {$tache->id}. Error: {$e->getMessage()}");
            }
        }

        return [
            'total' => (clone $baseQuery)->count(), 
            'nouveau' => (clone $baseQuery)->where('status', 'nouveau')->count(),
            'en_cours' => (clone $baseQuery)->where('status', 'en cours')->count(),
            'termine' => (clone $baseQuery)->where('status', 'termine')->count(),
            'overdue' => $overdueCount, // Use the calculated overdue count
        ];
    }

    private function getOverdueTasks($user)
    {
        $query = Tache::with('user')
                      ->where('datedebut', '<', Carbon::now())
                      ->where('status', '!=', 'termine');

        if (!$this->isAdmin($user)) {
            $query->where('iduser', $user->id);
        }

        return $query->orderBy('datedebut', 'asc')->limit(5)->get();
    }

    private function getRecentTasks($user)
    {
        $query = Tache::with('user')->orderBy('created_at', 'desc');

        if (!$this->isAdmin($user)) {
            $query->where('iduser', $user->id);
            // --- NEW ADDITION START for recent tasks in TacheController ---
            // Only show recent tasks whose datedebut has arrived for regular users
            $query->where('datedebut', '<=', Carbon::now());
            // --- NEW ADDITION END ---
        }

        return $query->limit(5)->get();
    }

    // Méthode pour exporter les tâches
    public function export(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format', 'csv');

        $query = Tache::with('user');
        
        if (!$this->isAdmin($user)) {
            $query->where('iduser', $user->id);
            // --- NEW ADDITION START for export tasks in TacheController ---
            // Only export tasks whose datedebut has arrived for regular users
            $query->where('datedebut', '<=', Carbon::now());
            // --- NEW ADDITION END ---
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
            fputcsv($file, ['ID', 'Description', 'Durée', 'Date début', 'Statut', 'Utilisateur', 'Créé le']);

            foreach ($taches as $tache) {
                fputcsv($file, [
                    $tache->id,
                    $tache->description,
                    $tache->duree,
                    $tache->datedebut,
                    $tache->status,
                    $tache->user->name ?? 'N/A',
                    $tache->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}