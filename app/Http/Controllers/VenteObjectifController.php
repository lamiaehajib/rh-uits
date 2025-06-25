<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VenteObjectif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenteObjectifController extends Controller
{
    function __construct()                                 

    {
         $this->middleware('permission:vente-list|vente-create|vente-edit|vente-delete', ['only' => ['index','show']]);

         $this->middleware('permission:vente-create', ['only' => ['create','store']]);

         $this->middleware('permission:vente-edit', ['only' => ['edit','update']]);

         $this->middleware('permission:vente-delete', ['only' => ['destroy']]);

         $this->middleware('permission:vente-show', ['only' => ['show']]);
    }
    public function index(Request $request)
{
    $user = auth()->user();
    $query = VenteObjectif::with('user');

    // If the user is an admin or has the 'ucgs' role
    if ($user->hasRole('Admin') || $user->hasRole('UCGS')) {
        // If there's a search query, filter the results
        if ($request->has('search') && $request->search != '') {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        $venteObjectifs = $query->paginate(5);
    } else {
        // Otherwise, retrieve only the tasks for the logged-in user
        if ($request->has('search') && $request->search != '') {
            $query->where('iduser', $user->id)
                  ->where('description', 'like', '%' . $request->search . '%');
        }
        $venteObjectifs = $query->paginate(10);
    }

    return view('vente_objectifs.index', compact('venteObjectifs'));
}


public function create()
{
    return view('vente_objectifs.create');
}

// Enregistrer un nouvel objectif de vente
public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'description' => 'required|string|max:255',
        'Quantitépc' => 'required|integer',
        'prixachat' => 'required|numeric',
        'totalachat' => 'required|numeric',
        'prixvendu' => 'required|numeric',
        'Quantitévendu' => 'required|integer',
        'totalvendu' => 'required|numeric',
        'marge' => 'required|numeric',
        'enstock' => 'required|integer',
    ]);

    try {
        // Ajouter l'objectif
        VenteObjectif::create(array_merge(
            $request->all(),
            ['iduser' => Auth::id()]
        ));

        // Message de succès
        return redirect()->route('vente_objectifs.create')->with('success', 'Objectif de vente créé avec succès.');
    } catch (\Exception $e) {
        // Message d'erreur
        return redirect()->route('vente_objectifs.create')->with('error', 'Une erreur est survenue lors de l’ajout.');
    }
}




    // Afficher un objectif de vente spécifique
    public function show($id)
    {
        $user = auth()->user();
        $venteObjectif = VenteObjectif::findOrFail($id);
    
        // Vérifiez si l'utilisateur a accès à cette tâche
        if ($user->hasRole('Admin') || $venteObjectif->iduser == $user->id) {
            return view('vente_objectifs.show', compact('venteObjectif'));
        }
    
        // Rediriger si l'utilisateur n'a pas accès
        return redirect()->route('vente_objectifs.index')->with('error', 'Accès refusé.');
    }

    // Afficher le formulaire d'édition
    public function edit($id)
    {
        $venteObjectif = VenteObjectif::findOrFail($id);
        $users = User::all();
        return view('vente_objectifs.edit', compact('venteObjectif', 'users'));
    }

    // Mettre à jour un objectif de vente existant
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'Quantitépc' => 'required|integer',
            'prixachat' => 'required|numeric',
            'totalachat' => 'required|numeric',
            'prixvendu' => 'required|numeric',
            'Quantitévendu' => 'required|integer',
            'totalvendu' => 'required|numeric',
            'marge' => 'required|numeric',
           
            'enstock' => 'required|integer',
        ]);

        $venteObjectif = VenteObjectif::findOrFail($id);
        $venteObjectif->update($request->all());

        return redirect()->route('vente_objectifs.index');
    }

    // Supprimer un objectif de vente
    public function destroy($id)
{
    $venteObjectif = VenteObjectif::find($id);

    if ($venteObjectif) {
        $venteObjectif->delete();
        return redirect()->route('vente_objectifs.index')->with('success', 'Vente supprimée avec succès.');
    }

    return redirect()->route('vente_objectifs.index')->with('error', 'Vente non trouvée.');
}

}
