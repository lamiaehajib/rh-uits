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
    $rendezVous = RendezVous::with(['projet.users', 'annulePar', 'reprogrammePar'])
        ->orderBy('date_heure', 'desc')
        ->paginate(10);

    return view('admin.rendez-vous.index', compact('rendezVous'));
}


    public function create()
    {
        // Eager load les utilisateurs pour le sélecteur de projets
        $projets = Projet::with('users')->get();
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
        
        // La colonne user_id a été supprimée, donc on ne l'assigne plus.
        // Le rendez-vous est maintenant lié uniquement au projet.
        RendezVous::create($validated);

        return redirect()->route('admin.rendez-vous.index')
            ->with('success', 'Rendez-vous créé avec succès!');
    }

   // Remplacer la méthode actuelle par celle-ci

public function show($id)
{
    // Chercher le rendez-vous par son ID et charger les relations immédiatement
    $rendezVous = RendezVous::with(['projet.users', 'annulePar', 'reprogrammePar', 'confirmePar'])
                            ->findOrFail($id);

    return view('admin.rendez-vous.show', compact('rendezVous'));
}

   public function edit($id)
{
    // Cherche le rendez-vous par son ID
    $rendezVous = RendezVous::find($id);

    // Si le rendez-vous n'existe pas, renvoie une erreur 404
    if (!$rendezVous) {
        abort(404, 'Rendez-vous introuvable');
    }

    $projets = Projet::with('users')->get();
    return view('admin.rendez-vous.edit', compact('rendezVous', 'projets'));
}

    public function update(Request $request, $id)
    {
         $rendezVous = RendezVous::find($id);
        $validated = $request->validate([
            'projet_id' => 'required|exists:projets,id',
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_heure' => 'required|date',
            'lieu' => 'nullable|string|max:255',
            'statut' => 'required|in:programmé,confirmé,terminé,annulé',
            'notes' => 'nullable|string'
        ]);
        
        // La colonne user_id a été supprimée, donc on ne l'assigne plus.
        $rendezVous->update($validated);

        return redirect()->route('admin.rendez-vous.show', $rendezVous)
            ->with('success', 'Rendez-vous mis à jour avec succès!');
    }


   public function cancelRendezVous(RendezVous $rendezVous)
{
    // Vérifier que l'utilisateur est associé au projet du rendez-vous
    if (!Auth::user()->projets()->where('projets.id', $rendezVous->projet_id)->exists()) {
        abort(403, 'Unauthorized action.');
    }

    if ($rendezVous->date_heure->isPast()) {
        return back()->with('error', 'Impossible d\'annuler un rendez-vous passé.');
    }

    // Mise à jour du statut et de l'utilisateur qui a annulé
    $rendezVous->update([
        'statut' => 'annulé',
        'annule_par_user_id' => Auth::id() // Cette ligne est cruciale
    ]);

    return back()->with('success', 'Rendez-vous annulé avec succès.');
}

    /**
     * Affiche le formulaire pour reprogrammer un rendez-vous annulé.
     *
     * @param \App\Models\RendezVous $rendezVous
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function reprogrammer(RendezVous $rendezVous)
    {
        // Vérifier que l'utilisateur est bien associé au projet et que le statut est 'annulé'
        if (!Auth::user()->projets()->where('projets.id', $rendezVous->projet_id)->exists() || $rendezVous->statut !== 'annulé') {
            return back()->with('error', 'Vous ne pouvez reprogrammer que les rendez-vous annulés qui vous concernent.');
        }

        return view('client.planning.reprogrammer', compact('rendezVous'));
    }

    /**
     * Met à jour le rendez-vous avec une nouvelle date et heure.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\RendezVous $rendezVous
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reprogramStore(Request $request, RendezVous $rendezVous)
{
    if (!Auth::user()->projets()->where('projets.id', $rendezVous->projet_id)->exists() || $rendezVous->statut !== 'annulé') {
        return back()->with('error', 'Action non autorisée.');
    }

    $validated = $request->validate([
        'date_heure' => 'required|date|after:now',
    ]);
    
    // Ajoute la nouvelle colonne 'reprogramme_par_user_id'
    $rendezVous->update([
        'date_heure' => $validated['date_heure'],
        'statut' => 'programmé',
        'annule_par_user_id' => null, // Remet à null l'utilisateur qui a annulé
        'reprogramme_par_user_id' => Auth::id(), // Enregistre l'utilisateur qui a reprogrammé
    ]);

    return redirect()->route('client.client.planning')
        ->with('success', 'Rendez-vous reprogrammé avec succès!');
}

    public function destroy(Request $request, $id)
{
    // Find the rendez-vous by its ID
    $rdv = RendezVous::findOrFail($id);
    
    // Call the delete() method on the found model instance
    $rdv->delete();
    
    return redirect()->route('admin.rendez-vous.index')
        ->with('success', 'Rendez-vous supprimé avec succès!');
}

    // Rendez-vous d'aujourd'hui
    public function aujourdhui()
    {
        $rendezVous = RendezVous::with('projet.users')
            ->aujourdhui()
            ->orderBy('date_heure')
            ->get();
        
        return view('admin.rendez-vous.aujourdhui', compact('rendezVous'));
    }

    // Planning de la semaine
    public function planning()
    {
        $rendezVous = RendezVous::with('projet.users')
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

        $rendezVous = RendezVous::with('projet.users')
            ->whereHas('projet', function ($query) use ($userId) {
                $query->whereHas('users', function ($q) use ($userId) {
                    $q->where('users.id', $userId);
                });
            })
            ->whereBetween('date_heure', [$startOfWeek, $endOfWeek])
            ->orderBy('date_heure')
            ->get();

        return view('client.planning.index', compact('rendezVous', 'periode'));
    }


    public function confirmRendezVous(RendezVous $rendezVous)
    {
        // Vérification des autorisations et du statut
        if (!Auth::user()->projets()->where('projets.id', $rendezVous->projet_id)->exists() || $rendezVous->statut !== 'programmé') {
            return back()->with('error', 'Impossible de confirmer ce rendez-vous.');
        }

        if ($rendezVous->date_heure->isPast()) {
            return back()->with('error', 'Impossible de confirmer un rendez-vous passé.');
        }

        // Mise à jour du statut et de l'utilisateur qui a confirmé
        $rendezVous->update([
            'statut' => 'confirmé',
            'confirme_par_user_id' => Auth::id(), // Enregistre l'ID du client qui a confirmé
        ]);

        return back()->with('success', 'Rendez-vous confirmé avec succès!');
    }
}