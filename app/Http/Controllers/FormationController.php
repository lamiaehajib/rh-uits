<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use App\Notifications\FormationCreatedNotification; // Assurez-vous que cette notification existe et est configurée correctement
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Pour utiliser Str::slug dans le nommage des fichiers

class FormationController extends Controller
{
    function __construct()
    {
        // Ces permissions doivent être définies dans votre système de permissions (ex: Spatie Permission)
        $this->middleware('permission:formation-list|formation-create|formation-edit|formation-delete|formation-show', ['only' => ['index','show']]);
        $this->middleware('permission:formation-create', ['only' => ['create','store']]);
        $this->middleware('permission:formation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:formation-delete', ['only' => ['destroy']]);
        // 'formation-show' est incluse dans le premier groupe, mais peut être laissée ici pour plus de clarté
        // $this->middleware('permission:formation-show', ['only' => ['show']]);
    }

    /**
     * Affiche la liste de toutes les formations.
     */
    public function index()
    {
        try {
            $user = auth()->user();
            $search = request()->get('search');
            $status = request()->get('status'); // Filtre par type de formation (en ligne, lieu)
            $statutFilter = request()->get('statut'); // Filtre par statut de formation (nouveu, encour, fini)
            $perPage = request()->get('per_page', 10); // Nombre d'éléments par page

            $query = Formation::with('users'); // Chargement de la relation des utilisateurs pour éviter le problème N+1

            // Logique de recherche et de filtrage pour les utilisateurs administrateurs (Admin ou )
            if ($user->hasRole('Admin') || $user->hasRole('Admin1')) {
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                          ->orWhere('status', 'like', '%' . $search . '%')
                          ->orWhere('nomformateur', 'like', '%' . $search . '%');
                    });
                }
                // Appliquer le filtre par type de formation
                if ($status) {
                    $query->where('status', $status);
                }
                // Appliquer le filtre par statut de formation
                if ($statutFilter) {
                    $query->where('statut', $statutFilter);
                }
                $formations = $query->orderBy('created_at', 'desc')->paginate($perPage);
            } else {
                // Logique de recherche et de filtrage pour les utilisateurs réguliers (ils ne voient que leurs formations)
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

            // Statistiques rapides pour le tableau de bord (vous pouvez les ajuster pour votre nouveau modèle)
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
        // Récupérer tous les utilisateurs pour pouvoir les associer à la formation
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
            'name' => 'required|string|max:255|unique:formations,name', // Le nom de la formation doit être unique
            'status' => 'required|in:en ligne,lieu', // Le type de formation (doit être l'une des valeurs spécifiées)
            'nomformateur' => 'required|string|max:255', // Le nom du formateur est requis
            'iduser' => 'required|array|min:1', // Au moins un utilisateur doit être sélectionné pour participer
            'iduser.*' => 'exists:users,id', // Les IDs des utilisateurs doivent exister dans la table des utilisateurs
            'date' => 'required|date|after_or_equal:today', // La date de début doit être aujourd'hui ou dans le futur
            'file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,mp4|max:10240', // Fichier optionnel (max 10MB)
            'statut' => 'required|in:fini,encour,nouveu', // Le statut de la formation (doit être l'une des valeurs spécifiées)
            'nombre_heures' => 'required|integer|min:1|max:1000', // Nombre d'heures (entier entre 1 et 1000)
            'nombre_seances' => 'required|integer|min:1|max:100', // Nombre de séances (entier entre 1 et 100)
            'prix' => 'required|numeric|min:0', // Le prix (numérique, supérieur ou égal à 0)
            'duree' => 'required|integer|min:1|max:365', // Durée en jours (entier entre 1 et 365)
        ], [
            // Messages d'erreur personnalisés
            'name.unique' => 'Une formation avec ce nom existe déjà.',
            'date.after_or_equal' => 'La date de la formation doit être aujourd\'hui ou dans le futur.',
            'iduser.min' => 'Vous devez sélectionner au moins un utilisateur.',
            'file.max' => 'La taille du fichier ne doit pas dépasser 10 Mo.',
            'file.mimes' => 'Le format de fichier n\'est pas pris en charge (PDF, DOC, DOCX, PNG, JPG, JPEG, MP4 uniquement).',
            'statut.in' => 'Le statut de la formation n\'est pas valide.',
        ]);

        // Début de la transaction de base de données pour assurer l'intégrité des données
        DB::beginTransaction();

        try {
            $filePath = null;
            // Gestion du téléchargement du fichier si présent
            if ($request->hasFile('file')) {
                $originalName = $request->file('file')->getClientOriginalName();
                // Création d'un nom de fichier unique et sécurisé en utilisant l'heure et le slug du nom original
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $request->file('file')->getClientOriginalExtension();
                $filePath = $request->file('file')->storeAs('formations', $fileName, 'public');
            }

            // Création de la formation dans la base de données
            $formation = Formation::create(array_merge(
                $request->except(['iduser', 'file']), // Exclure 'iduser' et 'file' car ils sont gérés séparément
                [
                    'file_path' => $filePath, // Enregistrer le chemin du fichier
                    'created_by' => auth()->id(), // Enregistrer l'ID de l'utilisateur qui a créé la formation
                ]
            ));

            // Associer les utilisateurs sélectionnés à la nouvelle formation (pour la relation Many-to-Many)
            $formation->users()->attach($request->iduser);

            // Envoi de notifications aux utilisateurs sélectionnés
            $usersToNotify = User::whereIn('id', $request->iduser)->get();
            foreach ($usersToNotify as $user) {
                try {
                    $user->notify(new FormationCreatedNotification($formation));
                } catch (\Exception $e) {
                    // Enregistrer un avertissement si l'envoi de la notification échoue pour un utilisateur spécifique
                    Log::warning('Échec de l\'envoi de la notification à l\'utilisateur ' . $user->id . ': ' . $e->getMessage());
                }
            }

            // Confirmer la transaction de base de données si tout s'est bien passé
            DB::commit();

            // Redirection avec un message de succès
            return redirect()->route('formations.index')->with('success', 'La formation a été créée avec succès.');

        } catch (\Exception $e) {
            // Annuler la transaction de base de données en cas d'erreur
            DB::rollback();
            // Enregistrer l'erreur détaillée dans les logs de Laravel
            Log::error('Erreur lors de la création de la formation: ' . $e->getMessage());
            // Rediriger vers le formulaire avec un message d'erreur et conserver les anciennes entrées
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
            'name' => 'required|string|max:255|unique:formations,name,' . $id, // Le nom est unique sauf pour la formation actuelle
            'status' => 'required|in:en ligne,lieu',
            'nomformateur' => 'required|string|max:255',
            'iduser' => 'required|array|min:1',
            'iduser.*' => 'exists:users,id',
            'date' => 'required|date', // Peut être une date passée lors de la mise à jour si nécessaire
            'file' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,mp4|max:10240',
            'statut' => 'required|in:fini,encour,nouveu', // Doit être requis car la valeur par défaut 'nouveu' ne suffit pas toujours
            'nombre_heures' => 'required|integer|min:1|max:1000',
            'nombre_seances' => 'required|integer|min:1|max:100',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1|max:365',
        ], [
            // Messages d'erreur personnalisés
            'name.unique' => 'Une formation avec ce nom existe déjà.',
            'iduser.min' => 'Vous devez sélectionner au moins un utilisateur.',
            'file.max' => 'La taille du fichier ne doit pas dépasser 10 Mo.',
            'file.mimes' => 'Le format de fichier n\'est pas pris en charge (PDF, DOC, DOCX, PNG, JPG, JPEG, MP4 uniquement).',
        ]);

        DB::beginTransaction();

        try {
            $formation = Formation::findOrFail($id);

            // Gestion du téléchargement du nouveau fichier
            if ($request->hasFile('file')) {
                // Supprimer l'ancien fichier si existant
                if ($formation->file_path && Storage::exists('public/' . $formation->file_path)) {
                    Storage::delete('public/' . $formation->file_path);
                }
                $originalName = $request->file('file')->getClientOriginalName();
                $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $request->file('file')->getClientOriginalExtension();
                $formation->file_path = $request->file('file')->storeAs('formations', $fileName, 'public');
            } elseif ($request->boolean('remove_file')) { // Optionnel: si une case à cocher est présente pour supprimer le fichier
                if ($formation->file_path && Storage::exists('public/' . $formation->file_path)) {
                    Storage::delete('public/' . $formation->file_path);
                    $formation->file_path = null;
                }
            }

            // Mise à jour des données de la formation
            $formation->update(array_merge(
                $request->except(['iduser', 'file', 'remove_file']),
                ['updated_by' => auth()->id()] // Enregistrer l'ID de l'utilisateur qui a mis à jour
            ));

            // Synchronisation des utilisateurs associés (ajoute, supprime et met à jour les relations)
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

            // Supprimer le fichier associé si existant
            if ($formation->file_path && Storage::exists('public/' . $formation->file_path)) {
                Storage::delete('public/' . $formation->file_path);
            }

            // Dissocier tous les utilisateurs liés à cette formation de la table pivot
            $formation->users()->detach();

            // Supprimer la formation elle-même
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

            // Vérification des permissions: Admin, , ou un utilisateur associé à la formation
            if (!$user->hasRole('Admin') && !$user->hasRole('Admin1') && !$formation->users->contains('id', $user->id)) {
                return redirect()->back()->with('error', 'Vous n\'avez pas la permission de télécharger ce fichier.');
            }

            // Vérifier si le fichier existe
            if (!$formation->file_path || !Storage::exists('public/' . $formation->file_path)) {
                return redirect()->back()->with('error', 'Le fichier n\'existe pas.');
            }

            // Retourner le fichier pour le téléchargement
            return Storage::download('public/' . $formation->file_path);
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du téléchargement du fichier.');
        }
    }

    /**
     * Récupère les statistiques des formations.
     */
    public function getStats()
    {
        try {
            $user = auth()->user();

            // Statistiques pour les administrateurs (Admin ou )
            if ($user->hasRole('Admin') || $user->hasRole('Admin1')) {
                $stats = [
                    'total_formations' => Formation::count(),
                    'formations_actives' => Formation::where('statut', 'encour')->count(),
                    'formations_terminees' => Formation::where('statut', 'fini')->count(),
                    'formations_nouvelles' => Formation::where('statut', 'nouveu')->count(),
                    'total_utilisateurs_inscrits' => DB::table('formation_user')->distinct('user_id')->count(),
                ];
            } else {
                // Statistiques pour les utilisateurs réguliers (uniquement leurs formations)
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

