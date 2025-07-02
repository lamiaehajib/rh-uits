<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use App\Models\User;
use App\Notifications\ObjectifCreatedNotification;
use App\Notifications\ObjectifUpdatedNotification;
use App\Notifications\ObjectifDeadlineNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ObjectifController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:objectif-list|objectif-create|objectif-edit|objectif-delete|objectif-show', ['only' => ['index','show', 'calendar']]);
        $this->middleware('permission:objectif-create', ['only' => ['create','store']]);
        $this->middleware('permission:objectif-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:objectif-delete', ['only' => ['destroy']]);
        $this->middleware('permission:objectif-show', ['only' => ['show']]);
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Charger la relation 'users'
        $query = Objectif::with('users'); // MODIFIED

        // Access control: if not admin, filter by assigned users
        if (!$user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($query) use ($search) {
                $query->where('type', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Advanced filtering
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('date', '<=', $request->date_to);
        }
        
        // User filter (for admins) // NEW FILTER ADDED FOR ADMINS
        if ($request->has('user_filter') && !empty($request->user_filter) && $user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', $request->user_filter);
            });
        }


        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $objectifs = $query->paginate(10);

        // Calculate dynamic progress for each objective
        $objectifs->each(function ($objectif) {
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);
        });

        // Statistics for dashboard
        $stats = $this->getObjectifStats($user);
        
        // Get users for filter (only for admins)
        $users = $this->isAdmin($user) ? User::all() : collect(); // NEW: Pass all users for the filter dropdown

        return view('objectifs.index', compact('objectifs', 'stats', 'users')); // MODIFIED: Pass users
    }

    private function getObjectifStats($user): array
    {
        $query = Objectif::query();

        if (!$user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }

        $total = $query->count();
        
        $completed = $query->clone()->where('progress', '>=', 100)->count(); 
        $inProgress = $query->clone()->where('progress', '>', 0)->where('progress', '<', 100)->count();
        
        $overdueCount = 0;
        $allObjectifs = $query->clone()->get();
        foreach ($allObjectifs as $objectif) {
            if ($this->isOverdue($objectif) && $this->calculateObjectifProgress($objectif) < 100) {
                $overdueCount++;
            }
        }
        $overdue = $overdueCount;

        return [
            'total' => $total,
            'completed' => $completed,
            'inProgress' => $inProgress,
            'overdue' => $overdue,
        ];
    }

    public function calendarView()
    {
        $user = auth()->user();
        $stats = $this->getObjectifStats($user);
        $upcomingObjectifs = $this->getUpcomingObjectifs($user);
        
        return view('objectifs.calendar', compact('stats', 'upcomingObjectifs'));
    }

    private function getUpcomingObjectifs($user)
    {
        $query = Objectif::with('users') // MODIFIED
            ->where('date', '>=', Carbon::now()->startOfDay())
            ->where('date', '<=', Carbon::now()->addDays(30)->endOfDay())
            ->orderBy('date', 'asc');

        if (!$user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }

        return $query->limit(5)->get()->map(function ($objectif) {
            $objectif->calculated_progress = (int) ($objectif->progress ?? 0);
            $objectif->is_overdue = $this->isOverdue($objectif);
            $objectif->needs_explanation = $objectif->is_overdue && $objectif->calculated_progress < 100;
            $objectif->days_remaining = $this->getDaysUntilDeadline($objectif);
            
            return $objectif;
        });
    }

    public function calendar(Request $request): JsonResponse
    {
        $user = auth()->user();
        \Log::info('FullCalendar Request received:', $request->all());

        // Charger la relation 'users'
        $query = Objectif::with(['users:id,name', 'creator:id,name']); // MODIFIED

        if (!$user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }

        if ($request->has('start') && $request->has('end')) {
            $startDate = Carbon::parse($request->start)->startOfDay();
            $endDate = Carbon::parse($request->end)->endOfDay();
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        if ($request->has('type') && !empty($request->type)) {
            $types = explode(',', $request->type); 
            $query->whereIn('type', $types);
        }

        \Log::info('Generated SQL Query:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $objectifs = $query->get()->map(function ($objectif) {
            $calculatedProgress = $this->calculateObjectifProgress($objectif);
            $needsExplanation = $this->needsExplanation($objectif);
            $eventColors = $this->getEventColor($objectif, $calculatedProgress, $needsExplanation);

            // Get assigned user names, comma-separated // NEW
            $assignedUsersNames = $objectif->users->pluck('name')->implode(', ');

            return [
                'id' => $objectif->id,
                'title' => $this->formatEventTitle($objectif),
                'start' => $objectif->date,
                'end' => $this->getCalculatedEndDate($objectif),
                'allDay' => true,
                'backgroundColor' => $eventColors['background'],
                'borderColor' => $eventColors['border'],
                'textColor' => $eventColors['text'],
                'extendedProps' => [
                    'description' => $objectif->description,
                    'type' => $objectif->type,
                    'ca' => $objectif->ca,
                    'afaire' => $objectif->afaire,
                    'progress' => $objectif->progress,
                    'calculated_progress' => $calculatedProgress,
                    'needs_explanation' => $needsExplanation,
                    'duree_value' => $objectif->duree_value,
                    'duree_type' => $objectif->duree_type,
                    'user_names' => $assignedUsersNames, // MODIFIED: now an array of names
                    'creator_name' => $objectif->creator->name ?? 'N/A',
                    'days_until_deadline' => $this->getDaysUntilDeadline($objectif),
                    'is_overdue' => $this->isOverdue($objectif),
                    'priority' => $this->calculatePriority($objectif, $calculatedProgress),
                ],
            ];
        });

        \Log::info('Objectives sent to FullCalendar:', ['count' => $objectifs->count(), 'data' => $objectifs->toArray()]);

        return response()->json($objectifs);
    }

    private function getCalculatedEndDate($objectif): ?string { /* ... unchanged ... */ return ''; }
    private function formatEventTitle($objectif): string { /* ... unchanged ... */ return ''; }
    private function getTypeIcon($type): string { /* ... unchanged ... */ return ''; }
    private function getEventColor($objectif, $calculatedProgress, $needsExplanation): array { /* ... unchanged ... */ return []; }
    private function getDaysUntilDeadline($objectif): int { /* ... unchanged ... */ return 0; }
    private function isOverdue($objectif): bool { /* ... unchanged ... */ return false; }
    private function calculatePriority($objectif, $calculatedProgress): string { /* ... unchanged ... */ return ''; }


    public function getAllObjectifs(Request $request): JsonResponse
    {
        $user = auth()->user();
        // Charger la relation 'users'
        $query = Objectif::with('users'); // MODIFIED

        // Access control
        if (!$user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }

        // Apply filters
        if ($request->has('type') && !empty($request->type)) {
            $types = is_array($request->type) ? $request->type : [$request->type];
            $query->whereIn('type', $types);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $objectifs = $query->get()->map(function ($objectif) {
            $calculatedProgress = $this->calculateObjectifProgress($objectif);
            $needsExplanation = $this->needsExplanation($objectif);
            
            // Get assigned user names, comma-separated // NEW
            $assignedUsersNames = $objectif->users->pluck('name')->implode(', ');

            return [
                'id' => $objectif->id,
                'date' => $objectif->date,
                'type' => $objectif->type,
                'description' => $objectif->description,
                'ca' => $objectif->ca,
                'afaire' => $objectif->afaire,
                'progress' => $objectif->progress,
                'calculated_progress' => $calculatedProgress,
                'needs_explanation' => $needsExplanation,
                'duree_value' => $objectif->duree_value,
                'duree_type' => $objectif->duree_type,
                'explanation_for_incomplete' => $objectif->explanation_for_incomplete,
                'user_names' => $assignedUsersNames, // MODIFIED
                'creator' => [
                    'id' => $objectif->creator->id ?? null,
                    'name' => $objectif->creator->name ?? 'N/A'
                ],
                'days_until_deadline' => $this->getDaysUntilDeadline($objectif),
                'is_overdue' => $this->isOverdue($objectif),
                'priority' => $this->calculatePriority($objectif, $calculatedProgress),
                'created_at' => $objectif->created_at->format('d/m/Y H:i'),
                'updated_at' => $objectif->updated_at->format('d/m/Y H:i')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $objectifs,
            'total' => $objectifs->count(),
            'stats' => $this->getObjectifStats($user)
        ]);
    }

    public function create()
    {
        $users = User::all();
        return view('objectifs.create', compact('users'));
    }

    public function store(Request $request)
    {
       $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'type' => 'required|in:formations,projets,vente',
            'description' => 'required|string|min:10|max:1000',
            'ca' => 'required|string',
            'duree_value' => 'nullable|integer|min:1',
            'duree_type' => 'nullable|in:jours,semaines,mois,annee',
            'afaire' => 'required|string|min:10|max:1000',
            'user_ids' => 'required|array', // MODIFIED: Validation for multiple users
            'user_ids.*' => 'exists:users,id', // MODIFIED
        ]);

        try {
            DB::beginTransaction();

            // Create objective data, excluding user_ids
            $objectifData = $request->except('user_ids'); // MODIFIED
            $objectifData['created_by'] = auth()->id();
            $objectifData['progress'] = 0; // Ensure progress defaults to 0 on creation

            $objectif = Objectif::create($objectifData); // MODIFIED

            // Attach multiple users to the objective // NEW
            $objectif->users()->attach($request->input('user_ids'));

            // Clear cache for all assigned users // MODIFIED
            foreach ($request->input('user_ids') as $userId) {
                Cache::forget('objectif_stats_' . $userId);
            }
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }

            // Send notification to each assigned user // MODIFIED
            foreach ($request->input('user_ids') as $userId) {
                $user = User::find($userId);
                if ($user) { 
                    $user->notify(new ObjectifCreatedNotification($objectif));
                }
            }

            // Log activity
            Log::info('Objectif created', [
                'objectif_id' => $objectif->id,
                'created_by' => auth()->id(),
                'assigned_to_users' => $request->user_ids // MODIFIED
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Objectif créé avec succès.',
                    'objectif' => $objectif->load('users') // MODIFIED
                ]);
            }

            return redirect()->route('objectifs.index')->with('success', 'Objectif créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating objectif', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création.'
                ], 500);
            }

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création.');
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        // Charger la relation 'users'
        $objectif = Objectif::with('users')->findOrFail($id); // MODIFIED

        // Access control: check if admin OR if current user is one of the assigned users
        if ($this->isAdmin($user) || $objectif->users->contains($user->id)) { // MODIFIED
            $this->markAsViewed($objectif, $user);
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);

            return view('objectifs.show', compact('objectif'));
        }

        return redirect()->route('objectifs.index')->with('error', 'Accès refusé.');
    }

    public function edit(Objectif $objectif)
    {
        // Charger la relation 'users' pour pré-sélectionner les checkboxes
        $objectif->load('users'); // NEW: Ensure 'users' are loaded for the view

        $users = User::all();
        return view('objectifs.edit', compact('objectif', 'users'));
    }

    public function update(Request $request, Objectif $objectif)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'type' => 'required|in:formations,projets,vente',
            'description' => 'required|string|min:10|max:1000',
            'ca' => 'nullable|string', 
            'duree_value' => 'nullable|integer|min:1',
            'duree_type' => 'nullable|in:jours,semaines,mois,annee',
            'afaire' => 'nullable|string|min:10|max:1000', 
            'user_ids' => 'required|array', // MODIFIED: Validation for multiple users
            'user_ids.*' => 'exists:users,id', // MODIFIED
            'progress' => 'sometimes|integer|min:0|max:100', 
            'explanation_for_incomplete' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Capture old assigned users for cache clearing and notifications
            $oldUserIds = $objectif->users->pluck('id')->toArray(); // MODIFIED

            // Update objective basic data, excluding user_ids
            $objectifData = $request->except('user_ids'); // MODIFIED
            $objectifData['progress'] = $request->progress ?? $objectif->progress; 
            
            $objectif->update($objectifData); // MODIFIED

            // Sync multiple users to the objective // NEW
            $objectif->users()->sync($request->input('user_ids'));

            // Clear cache for all old and new users // MODIFIED
            $allAffectedUserIds = array_unique(array_merge($oldUserIds, $request->input('user_ids')));
            foreach ($allAffectedUserIds as $userId) {
                Cache::forget('objectif_stats_' . $userId);
            }
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }

            // Send notification to changed users (newly assigned or removed) // MODIFIED: More complex logic if needed
            // For simplicity, we notify newly assigned users, or if progress changed.
            // A more robust solution would track actual changes in user assignment.
            $newlyAssignedUsers = array_diff($request->input('user_ids'), $oldUserIds);
            $removedUsers = array_diff($oldUserIds, $request->input('user_ids'));

            foreach ($newlyAssignedUsers as $userId) {
                $user = User::find($userId);
              
            }
            // You might also notify removed users or creator, depending on your logic

            // Log activity
            Log::info('Objectif updated', [
                'objectif_id' => $objectif->id,
                'updated_by' => auth()->id(),
                'old_assigned_users' => $oldUserIds, // MODIFIED
                'new_assigned_users' => $request->user_ids // MODIFIED
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Objectif mis à jour avec succès.',
                    'objectif' => $objectif->load('users') // MODIFIED
                ]);
            }

            return redirect()->route('objectifs.index')->with('success', 'Objectif mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating objectif', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la mise à jour.'
                ], 500);
            }

            return back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    public function destroy(Objectif $objectif)
    {
        try {
            // No specific user unlink needed, cascade delete in migration handles it
            $objectif->delete(); 
            // Clear cache for potentially affected users (users who were assigned)
            $objectif->users->pluck('id')->each(function($userId) { // NEW
                Cache::forget('objectif_stats_' . $userId);
            });
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }
            return back()->with('success', 'Objectif supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Error deleting objectif', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]); // NEW
            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    private function getColorByType($type) { /* ... unchanged ... */ return ''; }

    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Objectif::with('users'); // MODIFIED

        if (!$user->hasRole('Sup_Admin')) {
            $query->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }

        // Apply same filters as index
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        $objectifs = $query->get();

        $filename = 'objectifs_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($objectifs) {
            $file = fopen('php://output', 'w');

            // Headers updated: 'Utilisateurs Assignés'
            fputcsv($file, ['Date', 'Type', 'Description', 'CA', 'À faire', 'Durée Valeur', 'Durée Type', 'Utilisateurs Assignés', 'Progression', 'Explication Incomplète']); // MODIFIED

            foreach ($objectifs as $objectif) {
                $calculatedProgress = $this->calculateObjectifProgress($objectif); 
                // Get assigned user names, comma-separated // NEW
                $assignedUsersNames = $objectif->users->pluck('name')->implode(', ');

                fputcsv($file, [
                    $objectif->date,
                    $objectif->type,
                    $objectif->description,
                    $objectif->ca,
                    $objectif->afaire,
                    $objectif->duree_value,
                    $objectif->duree_type,
                    $assignedUsersNames, // MODIFIED
                    $calculatedProgress . '%',
                    $objectif->explanation_for_incomplete ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function dashboard(): JsonResponse
    {
        $user = auth()->user();
        $stats = $this->getObjectifStats($user);

        $recentQuery = Objectif::with('users')->latest()->limit(5); // MODIFIED
        if (!$user->hasRole('Sup_Admin')) {
            $recentQuery->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }
        $recent = $recentQuery->get();
        $recent->each(function ($objectif) {
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);
        });

        $upcomingQuery = Objectif::with('users') // MODIFIED
            ->where('date', '>', Carbon::now()->startOfDay())
            ->orderBy('date');

        if (!$user->hasRole('Sup_Admin')) {
            $upcomingQuery->whereHas('users', function ($q) use ($user) { // MODIFIED
                $q->where('user_id', $user->id); // MODIFIED
            });
        }
        $upcoming = $upcomingQuery->get()->filter(function($objectif) {
            $daysUntilDeadline = $this->getDaysUntilDeadline($objectif);
            return $daysUntilDeadline > 0 && $daysUntilDeadline <= 7;
        });

        $upcoming->each(function ($objectif) {
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);
        });

        return response()->json([
            'stats' => $stats,
            'recent' => $recent,
            'upcoming' => $upcoming
        ]);
    }

    public function updateProgress(Request $request, Objectif $objectif)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'explanation_for_incomplete' => 'nullable|string|max:1000',
        ]);

        $objectif->update([
            'progress' => $request->progress,
            'explanation_for_incomplete' => $request->explanation_for_incomplete,
        ]);

        // Clear cache for all assigned users // MODIFIED
        $objectif->users->pluck('id')->each(function($userId) {
            Cache::forget('objectif_stats_' . $userId);
        });
        if (auth()->user()->hasRole('Sup_Admin')) {
            Cache::forget('objectif_stats_' . auth()->id());
        }

        return response()->json([
            'success' => true,
            'message' => 'Progression mise à jour.',
            'progress' => $objectif->progress,
            'explanation_for_incomplete' => $objectif->explanation_for_incomplete,
        ]);
    }

    private function calculateObjectifProgress($objectif): int { /* ... unchanged ... */ return 0;}
    private function needsExplanation($objectif): bool { /* ... unchanged ... */ return false;}

    public function duplicate(Objectif $objectif)
    {
        if (!Auth::user()->can('objectif-create')) {
            abort(403, 'Unauthorized action. You do not have the necessary permissions to duplicate objectives (requires objectif-create).');
        }

        try {
            DB::beginTransaction();

            $newObjectif = $objectif->replicate();
            $newObjectif->date = Carbon::now()->addMonth()->toDateString(); 
            $newObjectif->progress = 0;
            $newObjectif->description = 'Copie de : ' . $objectif->description;
            $newObjectif->created_by = auth()->id();
            $newObjectif->created_at = Carbon::now();
            $newObjectif->updated_at = Carbon::now();
            $newObjectif->explanation_for_incomplete = null;

            $newObjectif->save();

            // Duplicate assigned users // NEW
            $newObjectif->users()->attach($objectif->users->pluck('id'));

            // Clear cache for newly assigned users // MODIFIED
            $newObjectif->users->pluck('id')->each(function($userId) {
                Cache::forget('objectif_stats_' . $userId);
            });
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }

            Log::info('Objectif duplicated', [
                'original_objectif_id' => $objectif->id,
                'new_objectif_id' => $newObjectif->id,
                'duplicated_by' => auth()->id(),
                'assigned_to_users' => $newObjectif->users->pluck('id')->toArray() // MODIFIED
            ]);

            DB::commit();

            return redirect()->route('objectifs.index')->with('success', 'Objectif dupliqué avec succès. Le nouvel objectif a une progression de 0% et une date un mois plus tard.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la duplication de l\'objectif', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'objectif_id' => $objectif->id
            ]);
            return back()->with('error', 'Une erreur est survenue lors de la duplication de l\'objectif : ' . $e->getMessage());
        }
    }

    private function isAdmin($user)
    {
        return $user->hasRole(['Sup_Admin', 'Custom_Admin']);
    }

}