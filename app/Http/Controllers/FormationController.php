<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use App\Notifications\FormationCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:formation-list|formation-create|formation-edit|formation-delete|formation-show', ['only' => ['index','show']]);
        $this->middleware('permission:formation-create', ['only' => ['create','store', 'duplicate']]);
        $this->middleware('permission:formation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:formation-delete', ['only' => ['destroy']]);
    }

    /**
     * Affiche la liste de toutes les formations.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $search = request()->get('search');
            $status = request()->get('status');
            $statutFilter = request()->get('statut');
            $perPage = request()->get('per_page', 10);

            $query = Formation::with('users');

            if ($user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin')) {
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('nomformateur', 'like', '%' . $search . '%');
                    });
                }
                if ($status) {
                    $query->where('status', $status);
                }
                if ($statutFilter) {
                    $query->where('statut', $statutFilter);
                }
                $formations = $query->orderBy('created_at', 'desc')->paginate($perPage);
            } else {
                $formations = Formation::with('users')
                    ->whereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    })
                    ->when($search, function ($q) use ($search) {
                        $q->where(function($subQ) use ($search) {
                            $subQ->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('status', 'like', '%' . $search . '%')
                                    ->orWhere('nomformateur', 'like', '%' . $search . '%');
                        });
                    })
                    ->when($status, function ($q) use ($status) {
                        $q->where('status', $status);
                    })
                    ->when($statutFilter, function ($q) use ($statutFilter) {
                        $q->where('statut', $statutFilter);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
            }

            $stats = [
                'total' => Formation::count(),
                'en_cours' => Formation::where('statut', 'encour')->count(),
                'terminées' => Formation::where('statut', 'fini')->count(),
                'nouvelles' => Formation::where('statut', 'nouveu')->count(),
            ];

            return view('formations.index', compact('formations', 'stats'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des formations: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des formations.');
        }
    }

    /**
     * Affiche le formulaire de création d'une nouvelle formation.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('formations.create', compact('users'));
    }

    /**
     * Enregistre une nouvelle formation dans la base de données.
     */
    public function store(Request $request)
    {
        // Règles de validation des données soumises par le formulaire
        $request->validate([
            'name' => 'required|string|max:255', // Supprimé 'unique:formations,name'
            'status' => 'required|in:en ligne,lieu',
            'nomformateur' => 'required|string|max:255',
            'iduser' => 'required|array|min:1',
            'iduser.*' => 'exists:users,id',
            'date' => 'required|date|after_or_equal:today',
            'file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,mp4|max:10240',
            'statut' => 'required|in:fini,encour,nouveu',
            'nombre_heures' => 'required|integer|min:1|max:1000',
            'nombre_seances' => 'required|integer|min:1|max:100',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1|max:365',
            'duree_unit' => 'required|string|in:jours,semaines,mois',
        ], [
           
            'date.after_or_equal' => 'La date de la formation doit être aujourd\'hui ou dans le futur.',
            'iduser.min' => 'Vous devez sélectionner au moins un utilisateur.',
            'file.max' => 'La taille du fichier ne doit pas dépasser 10 Mo.',
            'file.mimes' => 'Le format de fichier n\'est pas pris en charge (PDF, DOC, DOCX, PNG, JPG, JPEG, MP4 uniquement).',
            'statut.in' => 'Le statut de la formation n\'est pas valide.',
        ]);

        DB::beginTransaction();

        try {
            $filePath = null;
            if ($request->hasFile('file')) {
                $originalName = $request->file('file')->getClientOriginalName();
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $request->file('file')->getClientOriginalExtension();
                $filePath = $request->file('file')->storeAs('formations', $fileName, 'public');
            }

            $formation = Formation::create(array_merge(
                $request->except(['iduser', 'file']),
                [
                    'file_path' => $filePath,
                    'created_by' => auth()->id(),
                ]
            ));

            $formation->users()->attach($request->iduser);

            $usersToNotify = User::whereIn('id', $request->iduser)->get();
            foreach ($usersToNotify as $user) {
                try {
                    $user->notify(new FormationCreatedNotification($formation));
                } catch (\Exception $e) {
                    Log::warning('Échec de l\'envoi de la notification à l\'utilisateur ' . $user->id . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('formations.index')->with('success', 'La formation a été créée avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la formation: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue lors de la création de la formation. Veuillez réessayer.');
        }
    }

    /**
     * Affiche les détails d'une formation spécifique.
     */
    public function show($id)
    {
        try {
            $formation = Formation::with(['users', 'createdBy'])->findOrFail($id);
            return view('formations.show', compact('formation'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la formation (ID: ' . $id . '): ' . $e->getMessage());
            return redirect()->route('formations.index')->with('error', 'La formation n\'existe pas ou une erreur est survenue lors du chargement des détails.');
        }
    }

    /**
     * Affiche le formulaire d'édition d'une formation existante.
     */
    public function edit($id)
    {
        try {
            $formation = Formation::with('users')->findOrFail($id);
            $users = User::orderBy('name')->get();
            return view('formations.edit', compact('formation', 'users'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'édition de la formation: ' . $e->getMessage());
            return redirect()->route('formations.index')->with('error', 'La formation n\'existe pas.');
        }
    }

    /**
     * Met à jour une formation existante dans la base de données.
     */
    public function update(Request $request, $id)
    {
        // Règles de validation des données pour la mise à jour
        $request->validate([
            'name' => 'required|string|max:255', // Supprimé '|unique:formations,name,' . $id
            'status' => 'required|in:en ligne,lieu',
            'nomformateur' => 'required|string|max:255',
            'iduser' => 'required|array|min:1',
            'iduser.*' => 'exists:users,id',
            'date' => 'required|date',
            'file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,mp4|max:10240',
            'statut' => 'required|in:fini,encour,nouveu',
            'nombre_heures' => 'required|integer|min:1|max:1000',
            'nombre_seances' => 'required|integer|min:1|max:100',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1|max:365',
            'duree_unit' => 'required|string|in:jours,semaines,mois',
        ], [
            // Messages d'erreur personnalisés
            // 'name.unique' => 'Une formation avec ce nom existe déjà.', // Ce message n'est plus pertinent
            'iduser.min' => 'Vous devez sélectionner au moins un utilisateur.',
            'file.max' => 'La taille du fichier ne doit pas dépasser 10 Mo.',
            'file.mimes' => 'Le format de fichier n\'est pas pris en charge (PDF, DOC, DOCX, PNG, JPG, JPEG, MP4 uniquement).',
        ]);

        DB::beginTransaction();

        try {
            $formation = Formation::findOrFail($id);

            if ($request->hasFile('file')) {
                if ($formation->file_path && Storage::exists('public/' . $formation->file_path)) {
                    Storage::delete('public/' . $formation->file_path);
                }
                $originalName = $request->file('file')->getClientOriginalName();
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $request->file('file')->getClientOriginalExtension();
                $formation->file_path = $request->file('file')->storeAs('formations', $fileName, 'public');
            } elseif ($request->boolean('remove_file')) {
                if ($formation->file_path && Storage::exists('public/' . $formation->file_path)) {
                    Storage::delete('public/' . $formation->file_path);
                    $formation->file_path = null;
                }
            }

            $formation->update(array_merge(
                $request->except(['iduser', 'file', 'remove_file']),
                ['updated_by' => auth()->id()]
            ));

            $formation->users()->sync($request->iduser);

            DB::commit();

            return redirect()->route('formations.index')->with('success', 'La formation a été mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour de la formation: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Supprime une formation spécifique.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $formation = Formation::findOrFail($id);

            if ($formation->file_path && Storage::exists('public/' . $formation->file_path)) {
                Storage::delete('public/' . $formation->file_path);
            }

            $formation->users()->detach();

            $formation->delete();

            DB::commit();

            return redirect()->route('formations.index')->with('success', 'La formation a été supprimée avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la suppression de la formation: ' . $e->getMessage());
            return redirect()->route('formations.index')->with('error', 'Une erreur est survenue lors de la suppression de la formation.');
        }
    }

    /**
     * Télécharge un fichier de formation spécifique.
     */
    public function downloadFile($id)
    {
        try {
            $formation = Formation::findOrFail($id);
            $user = auth()->user();

            if (!$user->hasRole('Sup_Admin') && !$user->hasRole('Custom_Admin') && !$formation->users->contains('id', $user->id)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas la permission de télécharger ce fichier.');
            }

            if (!$formation->file_path || !Storage::exists('public/' . $formation->file_path)) {
                return redirect()->back()->with('error', 'Le fichier n\'existe pas.');
            }

            return Storage::download('public/' . $formation->file_path);
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du téléchargement du fichier.');
        }
    }

    /**
     * Duplicates a specific formation, including its associated file.
     */
public function duplicate($id)
    {
        try {
            $originalFormation = Formation::findOrFail($id);
            $user = auth()->user();

            if (!$user->can('formation-create')) {
                return redirect()->back()->with('error', 'Vous n\'avez pas la permission de dupliquer cette formation.');
            }

            DB::beginTransaction();

            $newFormation = $originalFormation->replicate();

            $newFormation->name = $originalFormation->name . ' (Copie ' . now()->format('Y-m-d H:i:s') . ' - ' . Str::random(4) . ')';

            $newFilePath = null;
            if ($originalFormation->file_path && Storage::disk('public')->exists($originalFormation->file_path)) {
                $originalFileName = pathinfo($originalFormation->file_path, PATHINFO_BASENAME);
                $originalExtension = pathinfo($originalFormation->file_path, PATHINFO_EXTENSION);
                $newFileName = time() . '_duplicate_' . Str::slug(pathinfo($originalFileName, PATHINFO_FILENAME)) . '.' . $originalExtension;
                $newFilePath = 'formations/' . $newFileName;

                Storage::disk('public')->copy($originalFormation->file_path, $newFilePath);
            }
            $newFormation->file_path = $newFilePath;

            $newFormation->created_by = auth()->id();
            $newFormation->updated_by = null; // Cette ligne est maintenant valide car la colonne est nullable et fillable.

            $newFormation->save();

            $newFormation->users()->attach($originalFormation->users->pluck('id'));

            DB::commit();

            return redirect()->route('formations.edit', $newFormation->id)->with('success', 'La formation a été dupliquée avec succès. Vous pouvez maintenant la modifier.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la duplication de la formation: ' . $e->getMessage());
            // Laissez ce message détaillé pour le moment si l'erreur persiste.
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la duplication de la formation: ' . $e->getMessage());
        }
    }


    /**
     * Récupère les statistiques des formations.
     */
    public function getStats()
    {
        try {
            $user = auth()->user();

            if ($user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin')) {
                $stats = [
                    'total_formations' => Formation::count(),
                    'formations_actives' => Formation::where('statut', 'encour')->count(),
                    'formations_terminees' => Formation::where('statut', 'fini')->count(),
                    'formations_nouvelles' => Formation::where('statut', 'nouveu')->count(),
                    'total_utilisateurs_inscrits' => DB::table('formation_user')->distinct('user_id')->count(),
                ];
            } else {
                $userFormations = $user->formations();
                $stats = [
                    'mes_formations' => $userFormations->count(),
                    'formations_en_cours' => $userFormations->where('statut', 'encour')->count(),
                    'formations_terminees' => $userFormations->where('statut', 'fini')->count(),
                    'formations_nouvelles' => $userFormations->where('statut', 'nouveu')->count(),
                ];
            }

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors du chargement des statistiques'], 500);
        }
    }
}