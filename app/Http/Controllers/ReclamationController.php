<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use App\Models\User;
// Removed: use App\Notifications\ReclamationCreatedNotification;
// Removed: use App\Notifications\ReclamationStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Still useful for general error logging, but not for notification errors anymore

class ReclamationController extends Controller
{
    /**
     * Affiche la liste des réclamations.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:reclamation-list|reclamation-create|reclamation-edit|reclamation-delete', ['only' => ['index','show']]);
        $this->middleware('permission:reclamation-create', ['only' => ['create','store']]);
        $this->middleware('permission:reclamation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:reclamation-delete', ['only' => ['destroy']]);
        $this->middleware('permission:reclamation-show', ['only' => ['show']]);
    }

    public function index()
    {
        $user = auth()->user();
        $search = request('search');
        $status = request('status');
        $priority = request('priority');
        $sortBy = request('sort_by', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        // Base query
        $query = Reclamation::with('user');

        // Admin sees all, users see only their own
        if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) { 
            $query->where('iduser', $user->id);
        }

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%$search%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortDirection);

        $reclamations = $query->paginate(10)->appends(request()->query());

        // Get statistics for dashboard
        $stats = $this->getReclamationStats($user);

        return view('reclamations.index', compact('reclamations', 'stats'));
    }

    /**
     * Get reclamation statistics
     */
    private function getReclamationStats($user)
    {
        // Start with the base query once
        $baseQuery = Reclamation::query();

        if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) { 
            $baseQuery->where('iduser', $user->id);
        }

        return [
            'total' => $baseQuery->count(), // Count total based on the base query
            // For each subsequent count, clone the base query
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $baseQuery)->where('status', 'resolved')->count(),
            'high_priority' => (clone $baseQuery)->where('priority', 'high')->count(),
            // For 'this_month', also clone the base query and apply the month filter
            'this_month' => (clone $baseQuery)->whereMonth('created_at', Carbon::now()->month)->count(),
        ];
    }

    /**
     * Affiche le formulaire de création d'une réclamation.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('reclamations.create', compact('users'));
    }

    /**
     * Enregistre une nouvelle réclamation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'date' => 'required|date',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|string|max:100',
            'attachments.*' => 'file|mimes:jpg,png,pdf,doc,docx|max:5120', // 5MB max
        ]);

        // Créer une nouvelle réclamation
        $reclamation = new Reclamation();
        $reclamation->titre = $request->titre;
        $reclamation->date = $request->date;
        $reclamation->description = $request->description;
        $reclamation->priority = $request->priority;
        $reclamation->category = $request->category;
        $reclamation->status = 'pending';
        $reclamation->iduser = auth()->user()->id;
        $reclamation->reference = $this->generateReference();

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('reclamations', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getClientMimeType()
                ];
            }
            $reclamation->attachments = json_encode($attachments);
        }

        $reclamation->save();

        // Removed notification sending logic for ReclamationCreatedNotification


        // Log activity
        activity()
            ->performedOn($reclamation)
            ->causedBy(auth()->user())
            ->log('Réclamation créée');

        return redirect()->route('reclamations.index')->with('success', 'Réclamation créée avec succès. Référence: ' . $reclamation->reference);
    }

    /**
     * Generate unique reference number
     */
    private function generateReference()
    {
        // Get the highest existing ID to ensure the new reference is always greater
        // than any existing auto-incremented reference number.
        // If no records exist, max('id') will return null, so start from 1.
        $lastReclamationId = Reclamation::max('id');
        $nextId = ($lastReclamationId) ? ($lastReclamationId + 1) : 1;

        return 'REC-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Affiche une réclamation spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) // ⬅️ Khass t'assurer annou machi Reclamation $reclamation
{
    $user = auth()->user();

    // ⬅️ L'MOUHIM: Ista3mel withTrashed() bach yjib hta Réclamation mamsouḥa
    $reclamation = Reclamation::withTrashed()
                           ->with(['user', 'activities'])
                           ->findOrFail($id);

    // -----------------------------------------------------------------
    // Vérification d'accès mou3addala:
    
    $isSupAdmin = $user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin');
    $isOwner = $reclamation->iduser == $user->id;
    $isTrashed = $reclamation->trashed();
    
    // Condition dyal l'Accès:
    // 1. Ya اما ykoun Sup/Custom Admin (ykoun 3endou l-accés dima)
    // 2. Ya اما ykoun Moul l-Réclamation w hiya machi mamsouḥa
    
    if ($isSupAdmin || ($isOwner && !$isTrashed)) { 
        return view('reclamations.show', compact('reclamation'));
    }

    // L-Halat dyal Accès Refusé:
    // Mamsouḥa wla machi mamsouḥa, ila kan l-user machi Admin wla machi Moul l-Réclamation, Accès Refusé
    if ($isTrashed) {
        return redirect()->route('reclamations.corbeille')->with('error', 'Accès refusé pour les éléments supprimés.');
    }
    
    // Ila kant active, w machi dyalek wla machi Admin, Accès refusé
    return redirect()->route('reclamations.index')->with('error', 'Accès refusé.');
}

    /**
     * Affiche le formulaire d'édition d'une réclamation.
     *
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reclamation $reclamation)
    {
        // Only load users for non-admins or if needed for display
        $users = (auth()->user()->hasRole('Sup_Admin') || auth()->user()->hasRole('Custom_Admin')) ? User::all() : collect([auth()->user()]); // Admins get all users, others only their own
        return view('reclamations.edit', compact('reclamation', 'users'));
    }

    /**
     * Met à jour une réclamation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reclamation $reclamation)
    {
        $user = auth()->user();

        if ($user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin')) { 
            $request->validate([
                'status' => 'required|in:pending,in_progress,resolved,closed',
                'admin_notes' => 'nullable|string',
            ]);

            $oldStatus = $reclamation->status;

            $reclamation->update([
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'resolved_at' => $request->status === 'resolved' ? now() : ($reclamation->resolved_at ?? null),
            ]);

            // Removed notification sending logic for ReclamationStatusUpdatedNotification

        } else {
            // Non-admins can update all fields except status and admin_notes
            $request->validate([
                'titre' => 'required|string|max:255',
                'date' => 'required|date',
                'description' => 'required|string',
                'priority' => 'required|in:low,medium,high',
                'category' => 'required|string|max:100',
                // 'iduser' is automatically set for non-admins, they shouldn't choose it.
                // It should also be required, but it's the current user's ID
            ]);

            // Ensure non-admins can only modify their own reclamations
            if ($reclamation->iduser != $user->id) {
                return redirect()->route('reclamations.index')->with('error', 'Accès refusé. Vous ne pouvez pas modifier cette réclamation.');
            }

            $reclamation->update([
                'titre' => $request->titre,
                'date' => $request->date,
                'description' => $request->description,
                'priority' => $request->priority,
                'category' => $request->category,
                // Do NOT update iduser here, it should remain the creator's ID
            ]);
        }

        // Log activity
        activity()
            ->performedOn($reclamation)
            ->causedBy($user)
            ->log('Réclamation mise à jour');

        return redirect()->route('reclamations.index')->with('success', 'Réclamation mise à jour avec succès.');
    }

    /**
     * Update reclamation status (API endpoint)
     */
    public function updateStatus(Request $request, Reclamation $reclamation)
    {
        // This method should primarily be used by admins or internal systems
        // if ($user->cannot('reclamation-edit-status')) // Example permission check
        // { abort(403); }

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'admin_notes' => 'nullable|string',
        ]);

        $oldStatus = $reclamation->status;

        $reclamation->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'resolved_at' => $request->status === 'resolved' ? now() : null, // Set resolved_at only if status becomes 'resolved'
        ]);

        // Removed notification sending logic for ReclamationStatusUpdatedNotification


        // Log activity
        activity()
            ->performedOn($reclamation)
            ->causedBy(auth()->user())
            ->log("Statut changé de '$oldStatus' à '{$request->status}'");

        return response()->json(['success' => true, 'message' => 'Statut mis à jour avec succès']);
    }
    /**
     * Supprime une réclamation.
     *
     * @param  \App\Models\Reclamation  $reclamation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reclamation $reclamation)
    {
        // Delete attachments
        if ($reclamation->attachments) {
            $attachments = json_decode($reclamation->attachments, true);
            foreach ($attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $reclamation->delete();

        // Log activity
        activity()
            ->performedOn($reclamation)
            ->causedBy(auth()->user())
            ->log('Réclamation supprimée');

        return redirect()->route('reclamations.index')->with('success', 'Réclamation supprimée avec succès.');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(Reclamation $reclamation, $attachmentIndex)
    {
        if (!$reclamation->attachments) {
            abort(404);
        }

        $attachments = json_decode($reclamation->attachments, true);

        if (!isset($attachments[$attachmentIndex])) {
            abort(404);
        }

        $attachment = $attachments[$attachmentIndex];
        $filePath = storage_path('app/public/' . $attachment['path']);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment['name']);
    }

    /**
     * Export reclamations to CSV
     */
    public function export()
    {
        $user = auth()->user();

        $query = Reclamation::with('user');
        if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) {
            $query->where('iduser', $user->id);
        }

        $reclamations = $query->get();

        $filename = 'reclamations_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($reclamations) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Référence', 'Titre', 'Description', 'Utilisateur',
                'Statut', 'Priorité', 'Catégorie', 'Date création',
                'Date résolution'
            ]);

            foreach ($reclamations as $reclamation) {
                fputcsv($file, [
                    $reclamation->reference,
                    $reclamation->titre,
                    $reclamation->description,
                    $reclamation->user->name,
                    $reclamation->status,
                    $reclamation->priority,
                    $reclamation->category,
                    $reclamation->created_at->format('Y-m-d H:i:s'),
                    $reclamation->resolved_at ? $reclamation->resolved_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get reclamation statistics for dashboard (This method seems to be for a separate reclamation dashboard, not the main one)
     */
    public function dashboard()
    {
        $user = auth()->user();
        $stats = $this->getReclamationStats($user);

        // Recent reclamations
        $query = Reclamation::with('user')->latest();
        if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) {
            $query->where('iduser', $user->id);
        }
        $recentReclamations = $query->take(5)->get();

        // Monthly statistics
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $query = Reclamation::whereYear('created_at', $date->year)
                                 ->whereMonth('created_at', $date->month);

            if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin')) {
                $query->where('iduser', $user->id);
            }

            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'count' => (clone $query)->count()
            ];
        }

        return view('reclamations.dashboard', compact('stats', 'recentReclamations', 'monthlyStats'));
    }

    public function corbeille()
{
    $reclamations = Reclamation::onlyTrashed()
                  ->with('user') 
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('reclamations.corbeille', compact('reclamations'));
}

// N°2. Restauration d'une Réclamation
public function restore($id)
{
    $reclamation = Reclamation::withTrashed()->findOrFail($id);
    $reclamation->restore();

    return redirect()->route('reclamations.corbeille')->with('success', 'Réclamation restaurée avec succès!');
}

// N°3. Suppression Définitive
public function forceDelete($id)
{
    $reclamation = Reclamation::withTrashed()->findOrFail($id);
    
    // ⚠️ Mola7aḍa: Ila bghiti tmass7 hta Activity Logs dyal Spatie, khass tzid had l-code:
    // Activity::forSubject($reclamation)->where('log_name', 'reclamation')->delete();
    
    $reclamation->forceDelete(); 

    return redirect()->route('reclamations.corbeille')->with('success', 'Réclamation supprimée définitivement!');
}
}
