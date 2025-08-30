<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Projet;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    public function index()
    {
        $rendezVous = RendezVous::with(['projet', 'client'])
            ->orderBy('date_heure', 'desc')
            ->paginate(10);
        
        return view('admin.rendez-vous.index', compact('rendezVous'));
    }

    public function create()
    {
        $projets = Projet::with('client')->get();
        return view('admin.rendez-vous.create', compact('projets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'projet_id' => 'required|exists:projets,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_heure' => 'required|date|after:now',
            'lieu' => 'nullable|string|max:255',
            'statut' => 'required|in:programmé,confirmé,terminé,annulé'
        ]);

        // Get user_id from projet
        $projet = Projet::findOrFail($validated['projet_id']);
        $validated['user_id'] = $projet->user_id;

        RendezVous::create($validated);

        return redirect()->route('admin.rendez-vous.index')
            ->with('success', 'Rendez-vous créé avec succès!');
    }

    public function show(RendezVous $rendezVous)
    {
        $rendezVous->load(['projet', 'client']);
        return view('admin.rendez-vous.show', compact('rendezVous'));
    }

    public function edit(RendezVous $rendezVous)
    {
        $projets = Projet::with('client')->get();
        return view('admin.rendez-vous.edit', compact('rendezVous', 'projets'));
    }

    public function update(Request $request, RendezVous $rendezVous)
    {
        $validated = $request->validate([
            'projet_id' => 'required|exists:projets,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_heure' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'statut' => 'required|in:programmé,confirmé,terminé,annulé',
            'notes' => 'nullable|string'
        ]);

        // Get user_id from projet
        $projet = Projet::findOrFail($validated['projet_id']);
        $validated['user_id'] = $projet->user_id;

        $rendezVous->update($validated);

        return redirect()->route('admin.rendez-vous.show', $rendezVous)
            ->with('success', 'Rendez-vous mis à jour avec succès!');
    }

    public function destroy(RendezVous $rendezVous)
    {
        $rendezVous->delete();

        return redirect()->route('admin.rendez-vous.index')
            ->with('success', 'Rendez-vous supprimé avec succès!');
    }

    // Rendez-vous d'aujourd'hui
    public function aujourdhui()
    {
        $rendezVous = RendezVous::with(['projet', 'client'])
            ->aujourdhui()
            ->orderBy('date_heure')
            ->get();
        
        return view('admin.rendez-vous.aujourdhui', compact('rendezVous'));
    }

    // Planning de la semaine
    public function planning()
    {
        $rendezVous = RendezVous::with(['projet', 'client'])
            ->whereBetween('date_heure', [now()->startOfWeek(), now()->endOfWeek()])
            ->orderBy('date_heure')
            ->get();
        
        return view('admin.rendez-vous.planning', compact('rendezVous'));
    }
}