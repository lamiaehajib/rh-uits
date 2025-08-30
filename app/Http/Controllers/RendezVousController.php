<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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


     public function cancelRendezVous(RendezVous $rendezVous)
    {
        // Check if the authenticated user is the owner of the rendezvous
        if (Auth::id() !== $rendezVous->projet->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // T'a99ed ila l'rendez-vous deja fâtte wa9tou, ila kân, ma y9derch y'annulih
        if ($rendezVous->date_heure->isPast()) {
            return back()->with('error', 'Impossible d\'annuler un rendez-vous passé.');
        }

        // Update le statut à 'annulé'
        $rendezVous->update(['statut' => 'annulé']);

        return back()->with('success', 'Rendez-vous annulé avec succès.');
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


  public function clientPlanning($periode = 'current_week')
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $now = Carbon::now();

        if ($periode === 'previous_week') {
            $startOfWeek = $now->copy()->subWeek()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $now->copy()->subWeek()->endOfWeek(Carbon::SUNDAY);
        } elseif ($periode === 'next_week') {
            $startOfWeek = $now->copy()->addWeek()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $now->copy()->addWeek()->endOfWeek(Carbon::SUNDAY);
        } else { // 'current_week'
            $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);
        }

        $rendezVous = RendezVous::with(['projet', 'projet.client'])
            ->whereHas('projet', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereBetween('date_heure', [$startOfWeek, $endOfWeek])
            ->orderBy('date_heure')
            ->get();

        return view('client.planning.index', compact('rendezVous', 'periode'));
    }
}