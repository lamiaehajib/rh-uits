<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\User;
use App\Notifications\TacheCreatedNotification;
use App\Notifications\TacheUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            // Appliquer le filtre datedebut uniquement si l'utilisateur n'est PAS un admin pour sa liste de tâches générale
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
            'description' => 'nullable|string|max:4000', // Laissez-le nullable juste pour la validation afin qu'il puisse être vide
            'audio_data' => 'nullable|string', // Doit être une chaîne car c'est une chaîne Base64
            'duree' => 'required|string|max:255',
            'datedebut' => 'required|date|after_or_equal:today',
            'status' => 'required|in:nouveau,en cours,termine',
            'date' => 'required|in:jour,semaine,mois',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'priorite' => 'required|in:faible,moyen,élevé',
            'retour' => 'nullable|string|max:5000',
        ]);

        // Si l'administrateur remplit les deux (description textuelle et audio_data)
        if ($request->filled('description') && $request->filled('audio_data')) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas saisir une description textuelle et un enregistrement audio en même temps. Choisissez-en un seul.')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except('user_ids', 'audio_data'); // 'audio_data' sera traitée manuellement
            $data['created_by'] = auth()->id();

            if ($request->filled('audio_data')) {
                // S'il y a des données audio (Base64)
                $data['description'] = '-'; // Le texte de la description sera "-" pour rester NOT NULL

                // Décoder l'audio Base64 et le stocker
                $base64_audio = $request->input('audio_data');
                // Extrayez les données Base64 réelles (supprimez "data:audio/webm;base64,")
                @list($type, $base64_audio) = explode(';', $base64_audio); // Utilisez @ pour supprimer l'avertissement si le format est inattendu
                @list(, $base64_audio) = explode(',', $base64_audio);
                $audio_decoded = base64_decode($base64_audio);

                $filename = 'audio_' . uniqid() . '.webm'; // Nom de fichier unique et format webm
                Storage::disk('public')->put('task_audios/' . $filename, $audio_decoded);
                $data['audio_description_path'] = 'task_audios/' . $filename;
            } else {
                // S'il n'y a pas de données audio
                $data['audio_description_path'] = null; // Le chemin du fichier audio sera NULL
                if ($request->filled('description')) {
                    // Si l'administrateur a rempli la description, laissez-la telle quelle
                    $data['description'] = $request->description;
                } else {
                    // Si l'administrateur a laissé la description vide, remplissez-la avec "-"
                    $data['description'] = '-';
                }
            }

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
            \Log::error("Erreur lors de la création de la tâche : " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la tâche. Détails : ' . $e->getMessage())
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

        // Récupérer tous les paramètres de requête de l'URL d'où vous venez (page d'index)
        // Cela conservera : search, status, date_filter, user_filter, sort_by, sort_direction, page.
        $filterParams = $request->query();

        return view('taches.show', compact('tache', 'filterParams')); // Passer filterParams à la vue
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
        // Passer tous les paramètres de filtre de la chaîne de requête URL actuelle à la vue d'édition.
        // C'est la clé : cela capture les filtres *originaux* (comme user_filter, et potentiellement status si défini).
        $filterParams = $request->query();
        return view('taches.edit', compact('tache', 'users', 'filterParams'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $tache = Tache::with('users')->findOrFail($id);
        $oldStatus = $tache->status;

        // Determine if the current user has "admin" level permissions for full task modification.
        // This includes 'Sup_Admin' and 'Custom_Admin'.
        $isAdminForFullEdit = $this->isAdmin($user);

        // Define roles that can modify only status and retour, assuming they are NOT full admins.
        // The Custom_Admin role is now covered by $isAdminForFullEdit.
        $canModifyStatusAndRetourOnlyRoles = ['USER_MULTIMEDIA', 'USER_TRAINING', 'Sales_Admin', 'USER_TECH'];

        // Flag to check if the user belongs to the specific roles that can only update status and retour.
        $canModifyStatusAndRetour = !$isAdminForFullEdit && $user->hasAnyRole($canModifyStatusAndRetourOnlyRoles);


        if ($isAdminForFullEdit) { // This block handles Sup_Admin and Custom_Admin
            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'audio_data' => 'nullable|string',
                'remove_existing_audio' => 'nullable|boolean',
                'duree' => 'required|string|max:255',
                'datedebut' => 'required|date',
                'status' => 'required|in:nouveau,en cours,termine',
                'date' => 'required|in:jour,semaine,mois',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'priorite' => 'required|in:faible,moyen,élevé',
                'retour' => 'nullable|string|max:5000', // ADDED: Validate 'retour' for admins
            ]);

            if ($request->filled('description') && $request->filled('audio_data')) {
                return redirect()->back()
                    ->with('error', 'Vous ne pouvez pas saisir une description textuelle et un enregistrement audio en même temps. Choisissez-en un seul.')
                    ->withInput();
            }

            // CHANGED: Do NOT exclude 'retour' from $data here.
            $data = $request->except('user_ids', 'audio_data', 'remove_existing_audio');
            $data['updated_by'] = auth()->id();

            if ($request->boolean('remove_existing_audio')) {
                if ($tache->audio_description_path) {
                    Storage::disk('public')->delete($tache->audio_description_path);
                }
                $data['audio_description_path'] = null;
                $data['description'] = $request->filled('description') ? $request->description : '-';
            } elseif ($request->filled('audio_data')) {
                if ($tache->audio_description_path) {
                    Storage::disk('public')->delete($tache->audio_description_path);
                }
                $data['description'] = '-';

                $base64_audio = $request->input('audio_data');
                @list($type, $base64_audio) = explode(';', $base64_audio);
                @list(, $base64_audio) = explode(',', $base64_audio);
                $audio_decoded = base64_decode($base64_audio);

                $filename = 'audio_' . uniqid() . '.webm';
                Storage::disk('public')->put('task_audios/' . $filename, $audio_decoded);
                $data['audio_description_path'] = 'task_audios/' . $filename;
            } elseif ($request->filled('description')) {
                if ($tache->audio_description_path) {
                    Storage::disk('public')->delete($tache->audio_description_path);
                }
                $data['audio_description_path'] = null;
                $data['description'] = $request->description;
            } else {
                $data['description'] = $tache->description;
                $data['audio_description_path'] = $tache->audio_description_path;

                if (empty($data['description']) && empty($data['audio_description_path'])) {
                    $data['description'] = '-';
                }
            }

            $startDate = Carbon::parse($request->input('datedebut'));
            $data['date_fin_prevue'] = $this->calculateExpectedEndDate($startDate, $request->input('duree'));

            $tache->update($data); // This will now include 'retour' if it was in $request->all()

            $tache->users()->sync($request->input('user_ids'));

        } elseif ($canModifyStatusAndRetour) { // This block handles USER_MULTIMEDIA, USER_TRAINING, Sales_Admin, USER_TECH
            $request->validate([
                'status' => 'required|in:nouveau,en cours,termine',
                'retour' => 'nullable|string|max:5000',
            ]);

            $tache->update([
                'status' => $request->status,
                'retour' => $request->retour,
                'updated_by' => auth()->id(),
            ]);
            \Log::info('Retour value after update for specific roles: ' . $tache->retour);
        } else {
            return redirect()->route('taches.index')
                ->with('error', 'Accès refusé pour modifier cette tâche.');
        }

        if ($oldStatus !== $tache->status) {
            foreach ($tache->users as $assignedUser) {
                $assignedUser->notify(new TacheUpdatedNotification($tache));
            }
        }

        $finalRedirectParams = $request->only([
            'search', 'date_filter', 'user_filter', 'sort_by', 'sort_direction', 'page'
        ]);

        if ($request->filled('original_status_filter') && $request->input('original_status_filter') !== 'all') {
            $finalRedirectParams['status'] = $request->input('original_status_filter');
        } else {
            unset($finalRedirectParams['status']);
        }

        return redirect()->route('taches.index', $finalRedirectParams)
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $tache = Tache::findOrFail($id);

        // Supprimer le fichier audio associé s'il existe
        if ($tache->audio_description_path) {
            Storage::disk('public')->delete($tache->audio_description_path);
        }

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
        // Ici, nous devons gérer la logique audio/description de la tâche d'origine
        if ($originalTache->audio_description_path) {
            // Si la tâche originale a un audio, nous le copions vers la nouvelle tâche
            // Cela nécessite de copier le fichier audio
            try {
                $originalAudioContent = Storage::disk('public')->get($originalTache->audio_description_path);
                $newFilename = 'audio_' . uniqid() . '.webm';
                Storage::disk('public')->put('task_audios/' . $newFilename, $originalAudioContent);
                $newTache->audio_description_path = 'task_audios/' . $newFilename;
                $newTache->description = '-'; // Si un audio est présent, la description devient '-'
            } catch (\Exception $e) {
                \Log::error("Erreur de duplication de l'audio pour la tâche " . $originalTache->id . ": " . $e->getMessage());
                $newTache->audio_description_path = null;
                $newTache->description = $originalTache->description; // Revenir au texte si la copie audio échoue
            }
        } else {
            // S'il n'y a pas d'audio, conserver la description textuelle
            $newTache->description = $originalTache->description . ' (Copie)';
            $newTache->audio_description_path = null;
        }
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
            \Log::warning("Format de durée inconnu pour la tâche : " . $duree);
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
                $descriptionToExport = $tache->description;

                // Ajuster la description pour l'export si elle est juste '-' et que l'audio existe
                if ($tache->description === '-' && $tache->audio_description_path) {
                    $descriptionToExport = 'Description audio disponible';
                }

                fputcsv($file, [
                    $tache->id,
                    $tache->titre,
                    $descriptionToExport, // Utiliser la description ajustée pour l'export
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


    public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les Tâches li mamsou7in
    // Load les relations li 3endek b7al 'creator' w 'users'
    $taches = Tache::onlyTrashed()
                  ->with(['creator', 'users']) 
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('taches.corbeille', compact('taches'));
}

// N°2. Restauration d'une Tâche (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-Tâche b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $tache = Tache::withTrashed()->findOrFail($id);
    $tache->restore();

    return redirect()->route('taches.corbeille')->with('success', 'Tâche restaurée avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-Tâche b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $tache = Tache::withTrashed()->findOrFail($id);
    $tache->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('taches.corbeille')->with('success', 'Tâche supprimée définitivement!');
}
}