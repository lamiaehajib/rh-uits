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

        if (!$this->isAdmin($user)) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
            // Apply datedebut filter only if the user is NOT an admin for their general tasks list
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
                    $query->where('status', '!=', 'termine')
                          ->whereNotNull('date_fin_prevue')
                          ->where('date_fin_prevue', '<', Carbon::now());
                    break;
                case 'future':
                    if ($this->isAdmin($user)) {
                        $query->where('datedebut', '>', Carbon::now());
                    }
                    break;
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
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'priorite' => 'required|in:faible,moyen,élevé',
            'retour' => 'nullable|string|max:5000',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->except('user_ids');
            $data['created_by'] = auth()->id();

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

   public function show($id, Request $request) 
    {
        $user = auth()->user();
        $tache = Tache::with(['users', 'creator'])->findOrFail($id);

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        // Nakhdou ga3 les query parameters men l-URL li kanti fihom (page index)
        // Hadchi ghay7tafed b: search, status, date_filter, user_filter, sort_by, sort_direction, page.
        $filterParams = $request->query(); 

        return view('taches.show', compact('tache', 'filterParams')); // Passi filterParams l-view
    }

    public function edit($id, Request $request)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id);

        if (!$this->hasAccessToTask($user, $tache)) {
            return redirect()->route('taches.index')
                                ->with('error', 'Accès refusé.');
        }

        $users = User::all();
        // Pass all filter parameters from the current URL query to the edit view.
        // This is key: it captures the *original* filters (like user_filter, and potentially status if set).
        $filterParams = $request->query(); 
        return view('taches.edit', compact('tache', 'users', 'filterParams'));
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
                'status' => 'required|in:nouveau,en cours,termine', // This is the task's own status field
                'date' => 'required|in:jour,semaine,mois',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'priorite' => 'required|in:faible,moyen,élevé',
            ]);

            $data = $request->except('retour', 'user_ids');
            $data['updated_by'] = auth()->id();

            $startDate = Carbon::parse($request->input('datedebut'));
            $data['date_fin_prevue'] = $this->calculateExpectedEndDate($startDate, $request->input('duree'));

            $tache->update($data);

            $tache->users()->sync($request->input('user_ids'));

        } elseif ($user->hasAnyRole(['USER_MULTIMEDIA', 'USER_TRAINING', 'Sales_Admin', 'USER_TECH'])) {
            $request->validate([
                'status' => 'required|in:nouveau,en cours,termine', // This is the task's own status field
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

        // --- THE FINAL KEY CHANGE IS HERE ---
        // Start with the full set of filters that were passed from the index page via URL query string.
        // These are guaranteed to be the *original* filters, not the task's modified status.
        $finalRedirectParams = $request->only([
            'search', 'date_filter', 'user_filter', 'sort_by', 'sort_direction', 'page'
        ]);

        // Now, *explicitly re-add the 'status' filter ONLY if it was present in the original query parameters.*
        // The `status` field from the form (`$request->input('status')`) should NOT become a filter unless
        // there was already a `status` filter in the original URL query.
        if ($request->has('status') && $request->input('status') !== 'all') { // Check if 'status' field exists and is not 'all' (meaning it was explicitly selected)
            // Now, we need to know if the original filter was set. The best way is to pass the original query string
            // as a separate parameter from the edit link, or use Session.
            // Since we're passing all `filterParams` from edit to update via hidden fields,
            // $request->input('status') from hidden field will reflect the original status filter if it was set.
            // However, the problem is that 'status' is also the *task's status*.

            // Let's assume `filterParams['status']` holds the original status filter from the URL.
            // If `filterParams['status']` is present and not 'all', then we keep it.
            // If it's not present or 'all', then we want no status filter.

            // The 'status' field in the form (`<select name="status" id="status">`) is ALWAYS sent.
            // So, `request()->input('status')` will always give the task's actual status.
            // But we want the *filter's* status from the index page.
            
            // The `filterParams` array from the `edit` method is passed via hidden inputs in the form.
            // So, to get the original 'status' filter, we should check `request()->input('status')` coming from the *hidden input*
            // that represents the original filter, not the task's status select.
            // To differentiate, we need to rename the hidden input for the *filter status*.

            // Let's adjust `edit.blade.php` first, then come back here.
            // Assuming `edit.blade.php` now passes `original_status_filter` if it exists.

            // Daba l-code dyal `update` method khassou ykoun haka:
            if ($request->filled('original_status_filter') && $request->input('original_status_filter') !== 'all') {
                $finalRedirectParams['status'] = $request->input('original_status_filter');
            } else {
                // If there was no original status filter or it was 'all', ensure it's not in the final URL.
                unset($finalRedirectParams['status']);
            }
        } else {
             // If the user is a non-admin and can only change status/retour, we still want to respect initial filters.
             if ($request->filled('original_status_filter') && $request->input('original_status_filter') !== 'all') {
                $finalRedirectParams['status'] = $request->input('original_status_filter');
            } else {
                unset($finalRedirectParams['status']);
            }
        }


        return redirect()->route('taches.index', $finalRedirectParams)
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
            \Log::warning("Unknown duration format for tache: " . $duree);
        }

        return $expectedEndDate;
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

        $overdueQuery = (clone $baseQuery)->where('status', '!=', 'termine')
                                           ->whereNotNull('date_fin_prevue')
                                           ->where('date_fin_prevue', '<', Carbon::now());

        $newTasks = (clone $baseQuery)->where('status', 'nouveau')->count();
        $inProgressTasks = (clone $baseQuery)->where('status', 'en cours')->count();
        $completedTasks = (clone $baseQuery)->where('status', 'termine')->count();

        return [
            'total' => (clone $baseQuery)->count(), 
            'nouveau' => $newTasks,
            'en_cours' => $inProgressTasks,
            'termine' => $completedTasks,
            'overdue' => $overdueQuery->count(),
        ];
    }

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
            fputcsv($file, ['ID', 'Titre', 'Description', 'Durée', 'Date début', 'Date Fin Prévue', 'Statut', 'Priorité', 'Retour', 'Utilisateurs Assignés', 'Créé le']);

            foreach ($taches as $tache) {
                $assignedUsers = $tache->users->pluck('name')->implode(', ');
                fputcsv($file, [
                    $tache->id,
                    $tache->titre,
                    $tache->description,
                    $tache->duree,
                    $tache->datedebut,
                    $tache->date_fin_prevue ? Carbon::parse($tache->date_fin_prevue)->format('d/m/Y') : '',
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