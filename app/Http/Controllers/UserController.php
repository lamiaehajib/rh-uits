<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Affichage avec recherche avancée, filtres et tri
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'DESC');
        $perPage = $request->input('per_page', 10);
        
        // Cache key pour optimiser les performances
        $cacheKey = 'users_list_' . md5(serialize($request->all()));
        
        $query = User::with('roles')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%')
                          ->orWhere('code', 'like', '%' . $search . '%')
                          ->orWhere('poste', 'like', '%' . $search . '%');
                });
            })
            ->when($roleFilter, function ($q) use ($roleFilter) {
                $q->whereHas('roles', function ($query) use ($roleFilter) {
                    $query->where('name', $roleFilter);
                });
            })
            ->when($statusFilter, function ($q) use ($statusFilter) {
                if ($statusFilter === 'active') {
                    $q->where('is_active', true);
                } elseif ($statusFilter === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->orderBy($sortBy, $sortDirection);

        $data = $query->paginate($perPage);
        $roles = Role::all();
        
        // Statistiques des utilisateurs
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'recent' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count()
        ];

        return view('users.index', compact('data', 'roles', 'stats'))
            ->with('i', ($request->input('page', 1) - 1) * $perPage);
    }

    /**
     * Export des utilisateurs en CSV/Excel
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        $users = User::with('roles')->get();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Nom', 'Email', 'Code', 'Téléphone', 'Poste', 'Adresse', 'Rôles', 'Statut', 'Date création']);
                
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->code,
                        $user->tele,
                        $user->poste,
                        $user->adresse,
                        $user->roles->pluck('name')->join(', '),
                        $user->is_active ? 'Actif' : 'Inactif',
                        $user->created_at->format('Y-m-d H:i:s')
                    ]);
                }
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Import des utilisateurs en masse
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx'
        ]);
        
        $file = $request->file('file');
        $path = $file->store('imports');
        
        try {
            $handle = fopen(storage_path('app/' . $path), 'r');
            $header = fgetcsv($handle);
            $importedCount = 0;
            $errors = [];
            
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    $userData = array_combine($header, $row);
                    
                    $user = User::create([
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make('123456'),
                        'code' => $userData['code'],
                        'tele' => $userData['tele'],
                        'poste' => $userData['poste'],
                        'adresse' => $userData['adresse'],
                        'repos' => $userData['repos'] ?? 'Lundi',
                        'is_active' => true
                    ]);
                    
                    if (isset($userData['role'])) {
                        $user->assignRole($userData['role']);
                    }
                    
                    $importedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($importedCount + 2) . ": " . $e->getMessage();
                }
            }
            
            fclose($handle);
            Storage::delete($path);
            
            $message = "$importedCount utilisateurs importés avec succès.";
            if (!empty($errors)) {
                $message .= " Erreurs: " . implode(', ', $errors);
            }
            
            return redirect()->route('users.index')->with('success', $message);
            
        } catch (\Exception $e) {
            Storage::delete($path);
            return redirect()->route('users.index')->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    /**
     * API pour recherche AJAX
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->input('q');
        
        $users = User::select('id', 'name', 'email', 'code', 'poste')
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->limit(10)
            ->get();
            
        return response()->json($users);
    }

    /**
     * Activation/Désactivation d'un utilisateur
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        
        $status = $user->is_active ? 'activé' : 'désactivé';
        
        // Log de l'action
        Log::info("Utilisateur {$status}", [
            'user_id' => $user->id,
            'changed_by' => auth()->id(),
            'timestamp' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "Utilisateur {$status} avec succès",
            'status' => $user->is_active
        ]);
    }

    /**
     * Suppression en masse
     */
    public function bulkDelete(Request $request)
    {
        $userIds = $request->input('user_ids', []);
        
        if (empty($userIds)) {
            return response()->json(['error' => 'Aucun utilisateur sélectionné'], 400);
        }
        
        try {
            $deletedCount = User::whereIn('id', $userIds)->delete();
            
            Log::info("Suppression en masse d'utilisateurs", [
                'deleted_count' => $deletedCount,
                'user_ids' => $userIds,
                'deleted_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "$deletedCount utilisateurs supprimés avec succès"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression'], 500);
        }
    }

    /**
     * Réinitialisation du mot de passe
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $newPassword = $this->generateRandomPassword();
        
        $user->password = Hash::make($newPassword);
        $user->password_changed_at = now();
        $user->save();
        
        // Envoyer notification par email
        $user->notify(new \App\Notifications\PasswordResetNotification($newPassword));
        
        Log::info("Mot de passe réinitialisé", [
            'user_id' => $user->id,
            'reset_by' => auth()->id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé et envoyé par email'
        ]);
    }

    /**
     * Duplication d'un utilisateur
     */
    public function duplicate($id)
    {
        $originalUser = User::with('roles')->findOrFail($id);
        
        $newUser = $originalUser->replicate();
        $newUser->email = 'copy_' . time() . '_' . $originalUser->email;
        $newUser->code = $originalUser->code + 1000; // Éviter les doublons
        $newUser->name = 'Copie de ' . $originalUser->name;
        $newUser->created_at = now();
        $newUser->save();
        
        // Copier les rôles
        $newUser->assignRole($originalUser->getRoleNames());
        
        return redirect()->route('users.edit', $newUser->id)
            ->with('success', 'Utilisateur dupliqué avec succès');
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'roles' => 'required',
            'tele' => 'required|string|max:20',
            'code' => 'required|integer|unique:users,code',
            'poste' => 'required|string|max:255',
            'salaire' => 'nullable|numeric|min:0',
            'adresse' => 'required|string|max:500',
            'repos' => 'required|array|min:1|max:2', // Permet 1 ou 2 jours
            'repos.*' => 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche', // Valide chaque jour
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
        ]);

        $input = $request->all();
        $password = $input['password'] ?? '123456';
        $input['password'] = Hash::make($password);
        $input['is_active'] = true;
        $input['password_changed_at'] = now();

        // Convertir le tableau de jours de repos en une chaîne séparée par des virgules
        $input['repos'] = implode(',', $request->input('repos'));

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        $siteUrl = config('app.url');
        $user->notify(new \App\Notifications\UserCreatedNotification($user->email, $password, $siteUrl));

        Cache::forget('users_list_*');
        Log::info("Nouvel utilisateur créé", [
            'user_id' => $user->id,
            'created_by' => auth()->id()
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès et e-mail envoyé.');
    }

    public function show($id)
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($id);
        
        // Statistiques de l'utilisateur
        $userStats = [
            'last_login' => $user->last_login_at,
            'login_count' => $user->login_count ?? 0,
            'created_ago' => $user->created_at->diffForHumans(),
            'role_count' => $user->roles->count()
        ];
        
        return view('users.show', compact('user', 'userStats'));
    }

      public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        // Convertir la chaîne 'repos' de l'utilisateur en tableau pour les cases à cocher
        // Si $user->repos est vide ou null, on initialise à un tableau vide.
        // $userRepos = $user->repos ? explode(separator: ',', $user->repos) : [];

        return view('users.edit',compact('user','roles','userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => ['nullable', 'same:confirm-password', Password::min(8)->letters()->numbers()],
            'roles' => 'required',
            'tele' => 'required|string|max:20',
            'code' => 'required|integer|unique:users,code,'.$id,
            'poste' => 'required|string|max:255',
            'salaire' => 'nullable|numeric|min:0',
            'adresse' => 'required|string|max:500',
            // VALIDATION: Expect an array for 'repos' with 1 or 2 items
            'repos' => 'required|array|min:1|max:2', 
            'repos.*' => 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche', 
        ]);

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
            $input['password_changed_at'] = now();
        } else {
            // Remove password from input if it's empty, so it's not updated with a null value
            $input = Arr::except($input, ['password']);
        }
        
        // Convert the array of 'repos' back to a comma-separated string before updating
        $input['repos'] = implode(',', $request->input('repos'));


        $user = User::findOrFail($id);
        $oldData = $user->toArray();
        
        $user->update($input);

        // Sync roles (assuming 'model_has_roles' is handled by spatie/laravel-permission package directly)
        // If you're manually deleting and re-assigning, ensure this is correct for your setup.
        // A simpler way with Spatie is often: $user->syncRoles($request->input('roles'));
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));

        // Log des modifications
        Log::info("Utilisateur modifié", [
            'user_id' => $user->id,
            'modified_by' => auth()->id(),
            'changes' => array_diff_assoc($input, $oldData)
        ]);

        // Vider le cache
        Cache::forget('users_list_*');

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Sauvegarder les données avant suppression pour le log
        $userData = $user->toArray();
        
        $user->delete();
        
        Log::info("Utilisateur supprimé", [
            'user_data' => $userData,
            'deleted_by' => auth()->id()
        ]);

        // Vider le cache
        Cache::forget('users_list_*');

        return redirect()->route('users.index')
                        ->with('success','Utilisateur supprimé avec succès.');
    }

    /**
     * Générer un mot de passe aléatoire sécurisé
     */
    private function generateRandomPassword($length = 12): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        return substr(str_shuffle($characters), 0, $length);
    }

    /**
     * Historique des actions d'un utilisateur
     */
    
}