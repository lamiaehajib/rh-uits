<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User; // Pour récupérer les utilisateurs
use App\Notifications\ProjectCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class ProjectController extends Controller
{
    function __construct()                                 

    {
         $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index','show']]);

         $this->middleware('permission:project-create', ['only' => ['create','store']]);

         $this->middleware('permission:project-edit', ['only' => ['edit','update']]);

         $this->middleware('permission:project-delete', ['only' => ['destroy']]);

         $this->middleware('permission:project-show', ['only' => ['show']]);
    }
    public function index(Request $request)
{
    $user = auth()->user();
    $search = $request->get('search');

    // If the user is an admin, retrieve all projects
    if ($user->hasRole('Admin') || $user->hasRole('ADMIN2')) {
        $projects = Project::with('users')
            ->when($search, function ($query, $search) {
                return $query->where('titre', 'like', "%{$search}%")
                             ->orWhere('nomclient', 'like', "%{$search}%")
                             ->orWhere('ville', 'like', "%{$search}%");
            })
            ->paginate(10);
    } else {
        // Otherwise, retrieve only the projects associated with the authenticated user
        $projects = Project::with('users')
            ->whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where('titre', 'like', "%{$search}%")
                             ->orWhere('nomclient', 'like', "%{$search}%")
                             ->orWhere('ville', 'like', "%{$search}%");
            })
            ->paginate(10);
    }

    return view('projects.index', compact('projects'));
}

    

    

    // Afficher le formulaire de création de projet
    public function create()
    {
        $users = User::all(); // Récupérer tous les utilisateurs pour les associer au projet
        return view('projects.create', compact('users'));
    }

    // Enregistrer un nouveau projet
    public function store(Request $request)
{
    $request->validate([
        'titre' => 'required|string|max:255',
        'nomclient' => 'required|string|max:255',
        'ville' => 'required|string|max:255',
        'bessoins' => 'required|string',
        'iduser' => 'required|array',
        'iduser.*' => 'exists:users,id',
        'date_project'=>'required|date',
    ]);

    $projectData = $request->except('iduser');
  

    // Créer le projet
    $project = Project::create($projectData);
    $project->users()->attach($request->iduser);

    // Notifier tous les utilisateurs associés au projet
    $users = User::whereIn('id', $request->iduser)->get();
    foreach ($users as $user) {
        $user->notify(new ProjectCreatedNotification($project));
    }

    return redirect()->route('projects.index')->with('success', 'Projet créé avec succès.');
}

    // Afficher un projet spécifique
    public function show($id)
    {
        $user = auth()->user();
    $project = project::findOrFail($id);

    // Vérifiez si l'utilisateur a accès à cette tâche
    if ($user->hasRole('Admin') || $project->iduser == $user->id) {
        return view('projects.show', compact('project'));
    }

    // Rediriger si l'utilisateur n'a pas accès
    return redirect()->route('projects.index')->with('error', 'Accès refusé.');
    }

    // Afficher le formulaire d'édition de projet
    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $users = User::all(); // Passer les utilisateurs à la vue d'édition
        return view('projects.edit', compact('project', 'users'));
    }

    // Mettre à jour un projet existant
    public function update(Request $request, $id)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'nomclient' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'bessoins' => 'required|string',
            'iduser' => 'required|array',
            'iduser.*' => 'exists:users,id',
            'date_project'=>'required|date',
        ]);
    
        $project = Project::findOrFail($id);
        $project->update($request->except('iduser'));
        $project->users()->sync($request->iduser);
    
        return redirect()->route('projects.index')->with('success', 'Projet mis à jour avec succès.');
    }

    // Supprimer un projet
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Projet supprimé avec succès.');
    }
}
