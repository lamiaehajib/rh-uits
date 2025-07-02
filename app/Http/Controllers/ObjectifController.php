<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use App\Models\User;
use App\Notifications\ObjectifCreatedNotification;
use App\Notifications\ObjectifUpdatedNotification;
use App\Notifications\ObjectifDeadlineNotification; // Keep if still used elsewhere for general deadlines
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str; // Make sure Str facade is imported

class ObjectifController extends Controller
{
    /**
     * Display a listing of the objectifs.
     *
     * @return \Illuminate\Http\Response
     */
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

        $query = Objectif::with('user');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($query) use ($search) {
                $query->where('type', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
                      // Removed 'status' from search
            });
        }

        // Advanced filtering
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Removed status filter as the column no longer exists
        // if ($request->has('status') && !empty($request->status)) {
        //     $query->where('status', $request->status);
        // }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Access control
        if ($user->hasRole('Sup_Admin')) {
            $objectifs = $query->paginate(10);
        } else {
            $objectifs = $query->where('iduser', $user->id)->paginate(10);
        }

        // Calculate dynamic progress for each objective
        $objectifs->each(function ($objectif) {
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);
        });

        // Statistics for dashboard
        $stats = $this->getObjectifStats($user);

        return view('objectifs.index', compact('objectifs', 'stats'));
    }

    /**
     * Get objectif statistics
     * Simplified to always use 0 for progress for stats.
     */
    private function getObjectifStats($user): array
    {
        $query = Objectif::query();

        if (!$user->hasRole('Sup_Admin')) {
            $query->where('iduser', $user->id);
        }

        $total = $query->count();
        
        // --- SIMPLIFIED STATS LOGIC ---
        // If you don't want calculations, these will just be based on raw `progress` field
        // assuming 0 is default and 100 means completed.
        $completed = $query->clone()->where('progress', '>=', 100)->count(); 
        $inProgress = $query->clone()->where('progress', '>', 0)->where('progress', '<', 100)->count();
        
        // For overdue, count objectives past their date AND not fully completed
        // This now correctly uses the calculated overdue status based on duree_value/type
        $overdueCount = 0;
        $allObjectifs = $query->clone()->get();
        foreach ($allObjectifs as $objectif) {
            if ($this->isOverdue($objectif) && $this->calculateObjectifProgress($objectif) < 100) {
                $overdueCount++;
            }
        }
        $overdue = $overdueCount;
        // --- END SIMPLIFIED STATS LOGIC ---

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
        
        // Get statistics for the sidebar or header
        $stats = $this->getObjectifStats($user);
        
        // Get upcoming objectives for quick preview
        $upcomingObjectifs = $this->getUpcomingObjectifs($user);
        
        return view('objectifs.calendar', compact('stats', 'upcomingObjectifs'));
    }

    /**
     * Get upcoming objectives for the next 30 days
     */
    private function getUpcomingObjectifs($user)
    {
        $query = Objectif::with('user')
            ->where('date', '>=', Carbon::now()->startOfDay())
            ->where('date', '<=', Carbon::now()->addDays(30)->endOfDay())
            ->orderBy('date', 'asc');

        if (!$user->hasRole('Sup_Admin')) {
            $query->where('iduser', $user->id);
        }

        return $query->limit(5)->get()->map(function ($objectif) {
            // Use existing 'progress' column if present, otherwise default to 0
            $objectif->calculated_progress = (int) ($objectif->progress ?? 0);
            
            // Simplified needsExplanation for upcoming objectives in sidebar
            $objectif->is_overdue = $this->isOverdue($objectif);
            $objectif->needs_explanation = $objectif->is_overdue && $objectif->calculated_progress < 100;
            
            $objectif->days_remaining = $this->getDaysUntilDeadline($objectif);
            
            // Ensure typeIcon is available for Blade to render, e.g., via model accessor
            // If Objectif model doesn't have getTypeIconAttribute() accessor, you can uncomment this:
            // $objectif->typeIcon = $this->getTypeIcon($objectif->type); 
            
            return $objectif;
        });
    }

    /**
     * Get all objectives for calendar display
     * Returns objectives with enhanced information for calendar
     */
    public function calendar(Request $request): JsonResponse
    {
        $user = auth()->user();

        // Ajoutez ces lignes pour voir ce que le contrôleur reçoit
        \Log::info('FullCalendar Request received:', $request->all());

        $query = Objectif::with(['user:id,name', 'creator:id,name']);

        if (!$user->hasRole('Sup_Admin')) {
            $query->where('iduser', $user->id);
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

        // Removed status filter as the column no longer exists
        // if ($request->has('status') && !empty($request->status)) {
        //     $statuses = explode(',', $request->status); 
        //     $query->whereIn('status', $statuses);
        // }

        // Pour voir la requête SQL générée
        \Log::info('Generated SQL Query:', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $objectifs = $query->get()->map(function ($objectif) {
            $calculatedProgress = $this->calculateObjectifProgress($objectif);
            $needsExplanation = $this->needsExplanation($objectif);
            $eventColors = $this->getEventColor($objectif, $calculatedProgress, $needsExplanation);

            return [
                'id' => $objectif->id,
                'title' => $this->formatEventTitle($objectif), // Uses updated formatEventTitle
                'start' => $objectif->date,
                'end' => $this->getCalculatedEndDate($objectif), // New method to get end date based on duration
                'allDay' => true, // Or false if you want to be more specific with time
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
                    'user_name' => $objectif->user->name ?? 'N/A',
                    'creator_name' => $objectif->creator->name ?? 'N/A',
                    'days_until_deadline' => $this->getDaysUntilDeadline($objectif),
                    'is_overdue' => $this->isOverdue($objectif),
                    'priority' => $this->calculatePriority($objectif, $calculatedProgress),
                ],
            ];
        });

        // Ajoutez cette ligne pour voir les données finales envoyées au frontend
        \Log::info('Objectives sent to FullCalendar:', ['count' => $objectifs->count(), 'data' => $objectifs->toArray()]);

        return response()->json($objectifs);
    }

    /**
     * Calculate the end date of an objective based on its start date and duration.
     */
    private function getCalculatedEndDate($objectif): ?string
    {
        if (empty($objectif->date) || empty($objectif->duree_value) || empty($objectif->duree_type)) {
            return null;
        }

        $startDate = Carbon::parse($objectif->date);
        $endDate = clone $startDate;

        switch ($objectif->duree_type) {
            case 'jours':
                $endDate->addDays($objectif->duree_value);
                break;
            case 'semaines':
                $endDate->addWeeks($objectif->duree_value);
                break;
            case 'mois':
                $endDate->addMonths($objectif->duree_value);
                break;
            case 'annee':
                $endDate->addYears($objectif->duree_value);
                break;
            default:
                return null;
        }

        return $endDate->toDateString();
    }

    /**
     * Format event title for calendar display
     */
    private function formatEventTitle($objectif): string
    {
        $typeIcon = $this->getTypeIcon($objectif->type);
        // Removed status badge as the column no longer exists
        $shortDescription = Str::limit($objectif->description ?? '', 40);
        
        return "{$typeIcon} {$shortDescription}";
    }

    /**
     * Get icon for objectif type
     */
    private function getTypeIcon($type): string
    {
        $icons = [
            'formations' => '📚',
            'projets' => '🚀',
            'vente' => '💰'
        ];

        return $icons[$type] ?? '📋';
    }

    /**
     * Get event color based on needsExplanation and calculatedProgress
     */
    private function getEventColor($objectif, $calculatedProgress, $needsExplanation): array
    {
        // Base colors by type
        $baseColors = [
            'formations' => ['#3498db', '#2980b9'], // Blue
            'projets' => ['#2ecc71', '#27ae60'], // Green
            'vente' => ['#e74c3c', '#c0392b'] // Red
        ];

        // Use the base color for the objective's type
        $baseColor = $baseColors[$objectif->type] ?? ['#95a5a6', '#7f8c8d']; // Default gray

        // Priority override for colors based on needsExplanation or calculatedProgress
        if ($needsExplanation) {
            return [
                'background' => '#e74c3c', // Strong red
                'border' => '#c0392b',
                'text' => '#ffffff'
            ];
        }

        if ($calculatedProgress >= 100) {
            return [
                'background' => '#2ecc71', // Strong green
                'border' => '#27ae60',
                'text' => '#ffffff'
            ];
        }

        if ($calculatedProgress >= 50) {
            return [
                'background' => '#f39c12', // Strong orange
                'border' => '#e67e22',
                'text' => '#ffffff'
            ];
        }

        // If not completed or overdue, use the type's base color
        return [
            'background' => $baseColor[0],
            'border' => $baseColor[1],
            'text' => '#ffffff'
        ];
    }

    /**
     * Calculate days until deadline based on 'duree_value' and 'duree_type'
     */
    private function getDaysUntilDeadline($objectif): int
    {
        if (empty($objectif->date) || empty($objectif->duree_value) || empty($objectif->duree_type)) {
            return -9999; // Or some other indicator for invalid duration
        }

        $startDate = Carbon::parse($objectif->date);
        $deadline = clone $startDate; // Clone to avoid modifying original $startDate

        switch ($objectif->duree_type) {
            case 'jours':
                $deadline->addDays($objectif->duree_value);
                break;
            case 'semaines':
                $deadline->addWeeks($objectif->duree_value);
                break;
            case 'mois':
                $deadline->addMonths($objectif->duree_value);
                break;
            case 'annee':
                $deadline->addYears($objectif->duree_value);
                break;
            default:
                return -9999; // Unknown duration type
        }

        // Ensure the deadline is considered at the end of the day for consistency with 'date' field
        $deadline->endOfDay();

        return Carbon::now()->diffInDays($deadline, false); // false to get negative for past dates
    }

    /**
     * Check if objectif is overdue based on 'duree_value' and 'duree_type'
     */
    private function isOverdue($objectif): bool
    {
        if (empty($objectif->date) || empty($objectif->duree_value) || empty($objectif->duree_type)) {
            return false; // Cannot determine overdue if duration is not set
        }

        $startDate = Carbon::parse($objectif->date);
        $deadline = clone $startDate;

        switch ($objectif->duree_type) {
            case 'jours':
                $deadline->addDays($objectif->duree_value);
                break;
            case 'semaines':
                $deadline->addWeeks($objectif->duree_value);
                break;
            case 'mois':
                $deadline->addMonths($objectif->duree_value);
                break;
            case 'annee':
                $deadline->addYears($objectif->duree_value);
                break;
            default:
                return false; // Unknown duration type
        }

        // Ensure the deadline is considered at the end of the day
        $deadline->endOfDay();

        // An objective is overdue if a valid deadline exists and the current time is past it.
        return Carbon::now()->greaterThan($deadline);
    }

    /**
     * Calculate priority based on deadline and (simplified) progress
     */
    private function calculatePriority($objectif, $calculatedProgress): string
    {
        $daysUntilDeadline = $this->getDaysUntilDeadline($objectif);
        
        // If it's overdue and not 100% complete, it's critical
        if ($this->isOverdue($objectif) && $calculatedProgress < 100) {
            return 'critique';
        }
        
        // If deadline is within 7 days and less than 50% complete, it's high
        // Ensure daysUntilDeadline is non-negative for this condition
        if ($daysUntilDeadline <= 7 && $calculatedProgress < 50 && $daysUntilDeadline >= 0) {
            return 'haute';
        }
        
        // If deadline is within 30 days and less than 25% complete, it's medium
        // Ensure daysUntilDeadline is non-negative for this condition
        if ($daysUntilDeadline <= 30 && $calculatedProgress < 25 && $daysUntilDeadline >= 0) {
            return 'moyenne';
        }
        
        return 'normale'; // Default
    }

    /**
     * Get all objectives with filters for export or analysis
     */
    public function getAllObjectifs(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = Objectif::with(['user:id,name', 'creator:id,name']);

        // Access control
        if (!$user->hasRole('Sup_Admin')) {
            $query->where('iduser', $user->id);
        }

        // Apply filters
        if ($request->has('type') && !empty($request->type)) {
            $types = is_array($request->type) ? $request->type : [$request->type];
            $query->whereIn('type', $types);
        }

        // Removed status filter as the column no longer exists
        // if ($request->has('status') && !empty($request->status)) {
        //     $statuses = is_array($request->status) ? $request->status : [$request->status];
        //     $query->whereIn('status', $statuses);
        // }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $objectifs = $query->get()->map(function ($objectif) {
            $calculatedProgress = $this->calculateObjectifProgress($objectif);
            $needsExplanation = $this->needsExplanation($objectif);
            
            return [
                'id' => $objectif->id,
                'date' => $objectif->date,
                'type' => $objectif->type,
                // 'status' => $objectif->status, // Removed status
                'description' => $objectif->description,
                'ca' => $objectif->ca,
                'afaire' => $objectif->afaire,
                'progress' => $objectif->progress,
                'calculated_progress' => $calculatedProgress,
                'needs_explanation' => $needsExplanation,
                'duree_value' => $objectif->duree_value, // Added
                'duree_type' => $objectif->duree_type,   // Added
                'explanation_for_incomplete' => $objectif->explanation_for_incomplete, // Added
                'user' => [
                    'id' => $objectif->user->id ?? null,
                    'name' => $objectif->user->name ?? 'N/A'
                ],
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

    /**
     * Show the form for creating a new objectif.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('objectifs.create', compact('users'));
    }

    /**
     * Store a newly created objectif in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'type' => 'required|in:formations,projets,vente',
            'description' => 'required|string|min:10|max:1000',
            'ca' => 'required|string',
            // 'status' => 'required|in:mois,annee', // Removed status validation
            'duree_value' => 'nullable|integer|min:1', // Added duree_value validation
            'duree_type' => 'nullable|in:jours,semaines,mois,annee', // Added duree_type validation
            'afaire' => 'required|string|min:10|max:1000',
            'iduser' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $objectif = new Objectif();
            $objectif->date = $request->date;
            $objectif->type = $request->type;
            $objectif->description = $request->description;
            $objectif->ca = $request->ca;
            // $objectif->status = $request->status; // Removed status assignment
            $objectif->duree_value = $request->duree_value; // Added
            $objectif->duree_type = $request->duree_type;   // Added
            $objectif->afaire = $request->afaire;
            $objectif->iduser = $request->iduser;
            $objectif->created_by = auth()->id();
            $objectif->progress = 0; // Ensure progress defaults to 0 on creation
            $objectif->save();

            // Clear cache
            Cache::forget('objectif_stats_' . $request->iduser);
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }

            // Get assigned user
            $user = User::find($request->iduser);

            // Send notification
            if ($user) { 
                $user->notify(new ObjectifCreatedNotification($objectif));
            }

            // Log activity
            Log::info('Objectif created', [
                'objectif_id' => $objectif->id,
                'created_by' => auth()->id(),
                'assigned_to' => $request->iduser
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Objectif créé avec succès.',
                    'objectif' => $objectif->load('user')
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

    /**
     * Display the specified objectif.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        $objectif = Objectif::with('user')->findOrFail($id);

        // Access control
        if ($user->hasRole('Sup_Admin') || $objectif->iduser == $user->id) {
            // Mark as viewed
            $this->markAsViewed($objectif, $user);

            // Calculate dynamic progress for the specific objective (using simplified method)
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);

            return view('objectifs.show', compact('objectif'));
        }

        return redirect()->route('objectifs.index')->with('error', 'Accès refusé.');
    }

    /**
     * Mark objectif as viewed
     */
    private function markAsViewed($objectif, $user)
    {
        $cacheKey = "objectif_viewed_{$objectif->id}_{$user->id}";

        if (!Cache::has($cacheKey)) {
            Cache::put($cacheKey, true, 3600); // 1 hour
        }
    }

    /**
     * Show the form for editing the specified objectif.
     *
     * @param  \App\Models\Objectif  $objectif
     * @return \Illuminate\Http\Response
     */
    public function edit(Objectif $objectif)
    {
        $users = User::all();
        return view('objectifs.edit', compact('objectif', 'users'));
    }

    /**
     * Update the specified objectif in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Objectif  $objectif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Objectif $objectif)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today', // Added date validation for update
            'type' => 'required|in:formations,projets,vente',
            'description' => 'required|string|min:10|max:1000',
            'ca' => 'nullable|string', 
            // 'status' => 'required|in:mois,annee', // Removed status validation
            'duree_value' => 'nullable|integer|min:1', // Added duree_value validation
            'duree_type' => 'nullable|in:jours,semaines,mois,annee', // Added duree_type validation
            'afaire' => 'nullable|string|min:10|max:1000', 
            'iduser' => 'required|exists:users,id',
            'progress' => 'sometimes|integer|min:0|max:100', 
            'explanation_for_incomplete' => 'nullable|string|max:1000', // Added validation for explanation
        ]);

        try {
            DB::beginTransaction();

            $oldUser = $objectif->iduser;

            $objectif->update([
                'date' => $request->date, // Added date to update
                'type' => $request->type,
                'description' => $request->description,
                'ca' => $request->ca,
                // 'status' => $request->status, // Removed status assignment
                'duree_value' => $request->duree_value, // Added
                'duree_type' => $request->duree_type,   // Added
                'afaire' => $request->afaire,
                'iduser' => $request->iduser,
                'progress' => $request->progress ?? $objectif->progress, 
                'explanation_for_incomplete' => $request->explanation_for_incomplete, // Added
                // 'updated_by' is not a fillable field in the model, it's usually handled by timestamps or a separate observer/event
                // If you need to track 'updated_by', ensure it's in the $fillable array of the Objectif model
            ]);

            // Clear cache for both old and new users
            Cache::forget('objectif_stats_' . $oldUser);
            Cache::forget('objectif_stats_' . $request->iduser);
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }

            // Send notification if user changed
            if ($oldUser != $request->iduser) {
                $user = User::find($request->iduser);
                if ($user) { 
                    $user->notify(new ObjectifUpdatedNotification($objectif));
                }
            }

            // Log activity
            Log::info('Objectif updated', [
                'objectif_id' => $objectif->id,
                'updated_by' => auth()->id(),
                'old_user' => $oldUser,
                'new_user' => $request->iduser
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Objectif mis à jour avec succès.',
                    'objectif' => $objectif->load('user')
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

    /**
     * Remove the specified objectif from storage.
     *
     * @param  \App\Models\Objectif  $objectif
     * @return \Illuminate\Http\Response
     */
   public function destroy(Objectif $objectif)
    {
        try {
            $objectif->delete();
            return back()->with('success', 'Objectif supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,assign_user', // Removed 'update_status'
            'objectifs' => 'required|array',
            'objectifs.*' => 'exists:objectifs,id',
            'value' => 'sometimes|string'
        ]);

        $user = auth()->user();
        $objectifs = Objectif::whereIn('id', $request->objectifs);

        if (!$user->hasRole('Sup_Admin')) {
            $objectifs->where('iduser', $user->id);
        }

        $objectifs = $objectifs->get();

        if ($objectifs->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Aucun objectif trouvé.']);
        }

        try {
            DB::beginTransaction();

            switch ($request->action) {
                case 'delete':
                    foreach ($objectifs as $objectif) {
                        $this->authorize('delete', $objectif);
                        $objectif->delete();
                    }
                    $message = count($objectifs) . ' objectifs supprimés.';
                    break;

                // Removed 'update_status' case
                // case 'update_status':
                //     $objectifs->each(function ($objectif) use ($request) {
                //         $this->authorize('update', $objectif);
                //         $objectif->update(['status' => $request->value]);
                //     });
                //     $message = count($objectifs) . ' objectifs mis à jour.';
                //     break;

                case 'assign_user':
                    $objectifs->each(function ($objectif) use ($request) {
                        $this->authorize('update', $objectif);
                        $objectif->update(['iduser' => $request->value]);
                    });
                    $message = count($objectifs) . ' objectifs réassignés.';
                    break;
            }

            // Clear relevant caches
            $userIds = $objectifs->pluck('iduser')->unique();
            foreach ($userIds as $userId) {
                Cache::forget('objectif_stats_' . $userId);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json(['success' => false, 'message' => 'Une erreur est survenue.']);
        }
    }

    /**
     * Get color by objectif type
     */
    private function getColorByType($type)
    {
        $colors = [
            'formations' => '#3498db',
            'projets' => '#2ecc71',
            'vente' => '#e74c3c'
        ];

        return $colors[$type] ?? '#95a5a6';
    }

    /**
     * Export objectives
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $query = Objectif::with('user');

        if (!$user->hasRole('Sup_Admin')) {
            $query->where('iduser', $user->id);
        }

        // Apply same filters as index
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }

        // Removed status filter as the column no longer exists
        // if ($request->has('status') && !empty($request->status)) {
        //     $query->where('status', $request->status);
        // }

        $objectifs = $query->get();

        $filename = 'objectifs_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($objectifs) {
            $file = fopen('php://output', 'w');

            // Headers (removed 'Status' and 'Priorité')
            fputcsv($file, ['Date', 'Type', 'Description', 'CA', 'À faire', 'Durée Valeur', 'Durée Type', 'Utilisateur', 'Progression Calculée', 'Explication Incomplète']);

            foreach ($objectifs as $objectif) {
                // Calculate progress before exporting - uses simplified method
                $calculatedProgress = $this->calculateObjectifProgress($objectif); 
                fputcsv($file, [
                    $objectif->date,
                    $objectif->type,
                    $objectif->description,
                    $objectif->ca,
                    // $objectif->status, // Removed status
                    $objectif->afaire,
                    $objectif->duree_value, // Added
                    $objectif->duree_type,  // Added
                    $objectif->user->name ?? 'N/A',
                    $calculatedProgress . '%',
                    $objectif->explanation_for_incomplete ?? '', // Added
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get dashboard data
     */
    public function dashboard(): JsonResponse
    {
        $user = auth()->user();
        $stats = $this->getObjectifStats($user);

        // Recent objectives
        $recentQuery = Objectif::with('user')->latest()->limit(5);
        if (!$user->hasRole('Sup_Admin')) {
            $recentQuery->where('iduser', $user->id);
        }
        $recent = $recentQuery->get();
        $recent->each(function ($objectif) {
            $objectif->calculated_progress = $this->calculateObjectifProgress($objectif);
            $objectif->needs_explanation = $this->needsExplanation($objectif);
        });

        // Upcoming deadlines
        $upcomingQuery = Objectif::with('user')
            ->where('date', '>', Carbon::now()->startOfDay())
            // This filter needs to be dynamic based on duree_value and duree_type
            // For simplicity, we'll fetch and then filter in PHP for now, or refine query
            ->orderBy('date');

        if (!$user->hasRole('Sup_Admin')) {
            $upcomingQuery->where('iduser', $user->id);
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

    /**
     * Update progress
     */
    public function updateProgress(Request $request, Objectif $objectif)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'explanation_for_incomplete' => 'nullable|string|max:1000', // Added validation for explanation
        ]);

        $objectif->update([
            'progress' => $request->progress,
            'explanation_for_incomplete' => $request->explanation_for_incomplete, // Added
            // 'updated_by' is not a fillable field in the model, it's usually handled by timestamps or a separate observer/event
            // If you need to track 'updated_by', ensure it's in the $fillable array of the Objectif model
        ]);

        // Clear cache
        Cache::forget('objectif_stats_' . $objectif->iduser);

        return response()->json([
            'success' => true,
            'message' => 'Progression mise à jour.',
            'progress' => $objectif->progress,
            'explanation_for_incomplete' => $objectif->explanation_for_incomplete,
        ]);
    }

    /**
     * Calculate the dynamic progress of an objective.
     * This version ignores 'ca' and 'afaire' and relies solely on the 'progress' column.
     *
     * @param \App\Models\Objectif $objectif
     * @return int
     */
    private function calculateObjectifProgress($objectif): int
    {
        return (int) ($objectif->progress ?? 0);
    }

    /**
     * Determines if a user needs to provide an explanation for an uncompleted objective.
     * This version is simplified to depend only on calculated_progress and overdue status.
     *
     * @param \App\Models\Objectif $objectif
     * @return bool
     */
    private function needsExplanation($objectif): bool
    {
        $calculatedProgress = $this->calculateObjectifProgress($objectif);
        $isOverdue = $this->isOverdue($objectif);

        return $isOverdue && $calculatedProgress < 100 && empty($objectif->explanation_for_incomplete);
    }


    public function duplicate(Objectif $objectif)
    {
        // === LOGIQUE D'AUTORISATION UNIQUE : VÉRIFIE SEULEMENT 'objectif-create' ===
        // Si l'utilisateur n'a PAS la permission 'objectif-create', il n'est pas autorisé.
        if (!Auth::user()->can('objectif-create')) {
            abort(403, 'Unauthorized action. You do not have the necessary permissions to duplicate objectives (requires objectif-create).');
        }

        // Si tu avais une politique (Policy) `ObjectifPolicy` avec une méthode `duplicate`,
        // tu devrais la supprimer ou la commenter, ou t'assurer qu'elle vérifie aussi uniquement 'objectif-create'.
        // Par exemple:
        // public function duplicate(User $user, Objectif $objectif) {
        //      return $user->can('objectif-create');
        // }


        try {
            DB::beginTransaction();

            $newObjectif = $objectif->replicate();
            // Adjust date based on original date and duration, or set a default like +1 month
            // For simplicity, setting to 1 month from now for duplicated objective
            $newObjectif->date = Carbon::now()->addMonth()->toDateString(); 
            $newObjectif->progress = 0;
            $newObjectif->description = 'Copie de : ' . $objectif->description;
            $newObjectif->created_by = auth()->id();
            $newObjectif->created_at = Carbon::now();
            $newObjectif->updated_at = Carbon::now();
            $newObjectif->explanation_for_incomplete = null; // Clear explanation for incomplete for duplicated objective

            $newObjectif->save();

            Cache::forget('objectif_stats_' . $newObjectif->iduser);
            if (auth()->user()->hasRole('Sup_Admin')) {
                Cache::forget('objectif_stats_' . auth()->id());
            }

            Log::info('Objectif duplicated', [
                'original_objectif_id' => $objectif->id,
                'new_objectif_id' => $newObjectif->id,
                'duplicated_by' => auth()->id(),
                'assigned_to' => $newObjectif->iduser
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
}
