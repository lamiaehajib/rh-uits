<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role; // N'oublie pas d'importer le modèle de rôle

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projets = Projet::with('client')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.projets.index', compact('projets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Correction : Récupérer tous les utilisateurs avec le rôle 'Client'
        $clients = User::role('Client')->get();
        return view('admin.projets.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'statut_projet' => 'required|in:en cours,terminé,en attente,annulé'
        ]);

        // Handle file upload
        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('projets', 'public');
        }

        Projet::create($validated);

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet créé avec succès!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function show(Projet $projet)
    {
        $projet->load(['client', 'rendezVous', 'avancements']);
        $pourcentageGlobal = $projet->avancements->avg('pourcentage') ?? 0;
        
        return view('admin.projets.show', compact('projet', 'pourcentageGlobal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function edit(Projet $projet)
    {
        // Correction : Récupérer tous les utilisateurs avec le rôle 'Client'
        $clients = User::role('Client')->get();
        return view('admin.projets.edit', compact('projet', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Projet $projet)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
            'statut_projet' => 'required|in:en cours,terminé,en attente,annulé'
        ]);

        // Handle file upload
        if ($request->hasFile('fichier')) {
            // Delete old file if exists
            if ($projet->fichier) {
                Storage::disk('public')->delete($projet->fichier);
            }
            $validated['fichier'] = $request->file('fichier')->store('projets', 'public');
        }

        $projet->update($validated);

        return redirect()->route('admin.projets.show', $projet)
            ->with('success', 'Projet mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Projet $projet)
    {
        // Delete associated file
        if ($projet->fichier) {
            Storage::disk('public')->delete($projet->fichier);
        }

        $projet->delete();

        return redirect()->route('admin.projets.index')
            ->with('success', 'Projet supprimé avec succès!');
    }

    /**
     * Display dashboard statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $stats = [
            'total_projets' => Projet::count(),
            'projets_en_cours' => Projet::parStatut('en cours')->count(),
            'projets_termines' => Projet::parStatut('terminé')->count(),
            'projets_en_attente' => Projet::parStatut('en attente')->count(),
        ];

        $projets_recents = Projet::with('client')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'projets_recents'));
    }
}
