<?php

namespace App\Http\Controllers;

use App\Models\Avancement;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role; // N'oublie pas d'importer le modèle de rôle
use Illuminate\Support\Facades\Auth;


class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projets = Projet::with('users')
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
            'client_ids' => 'required|array', // Le champ est maintenant un tableau
            'client_ids.*' => 'exists:users,id', // Chaque élément du tableau doit exister
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,csv,xls,xlsx|max:5120',
            'statut_projet' => 'required|in:en cours,terminé,en attente,annulé'
        ]);

        if ($request->hasFile('fichier')) {
            $validated['fichier'] = $request->file('fichier')->store('projets', 'public');
        }

        $projet = Projet::create($validated);
        $projet->users()->sync($request->client_ids); // Attacher les clients

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
    $projet->load(['users', 'rendezVous', 'avancements']);

    // Ancien code (moyenne) :
    // $pourcentageGlobal = $projet->avancements->avg('pourcentage') ?? 0;

    // Nouveau code (somme simple) :
    $pourcentageGlobal = $projet->avancements->sum('pourcentage');

    // Assurez-vous que le pourcentage ne dépasse pas 100%
    if ($pourcentageGlobal > 100) {
        $pourcentageGlobal = 100;
    }

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
            'client_ids' => 'required|array', // Le champ est maintenant un tableau
            'client_ids.*' => 'exists:users,id', // Chaque élément du tableau doit exister
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            // Ajout du format xlsx à la règle de validation
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,csv,xls,xlsx|max:5120',
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
        $projet->users()->sync($request->client_ids);

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

        $projets_recents = Projet::with('users')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'projets_recents'));
    }


    public function showAvancement(Avancement $avancement)
    {
        // Ta'aked belli l'client 3ando l7a9 ychouf had l'avancement
        if ($avancement->projet->user_id !== auth()->id()) {
            abort(403);
        }

        return view('client.avancements.show', compact('avancement'));
    }




    public function downloadFile(Projet $projet)
    {
        // Ta'aked men l-woujoud dyal l-fichier
        if (!$projet->fichier || !Storage::disk('public')->exists($projet->fichier)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Njebdou l'ism l'asli dyal l'fichier
        $originalFileName = basename($projet->fichier);

        // N'ssamiw l'fichier b'smya l'projet + l'ism l'asli dyalou
        $fileName = 'Projet_' . $projet->titre . '_' . $originalFileName;

        // Télécharger l-fichier b'smiya jdida
        return Storage::disk('public')->download($projet->fichier, $fileName);
    }
}
