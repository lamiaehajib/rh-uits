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
  
    public function index(Request $request)
{
    // === 1. FILTRES AVANCÉS ===
    $query = Projet::with(['users', 'avancements', 'rendezVous']);
    
    // Filter b statut
    if ($request->filled('statut')) {
        $query->where('statut_projet', $request->statut);
    }
    
    // Filter b client
    if ($request->filled('client_id')) {
        $query->whereHas('users', function($q) use ($request) {
            $q->where('users.id', $request->client_id);
        });
    }
    
    // Filter b date (intervalle)
    if ($request->filled('date_debut_filter')) {
        $query->where('date_debut', '>=', $request->date_debut_filter);
    }
    if ($request->filled('date_fin_filter')) {
        $query->where('date_fin', '<=', $request->date_fin_filter);
    }
    
    // Search bar (titre ou description)
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('titre', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }
    
    // Tri (sorting)
    $sortBy = $request->get('sort_by', 'created_at');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);
    
    $projets = $query->paginate(10)->withQueryString();
    
    
    // === 2. STATISTIQUES GLOBALES ===
    $stats = [
        'total_projets' => Projet::count(),
        'projets_en_cours' => Projet::where('statut_projet', 'en cours')->count(),
        'projets_termines' => Projet::where('statut_projet', 'terminé')->count(),
        'projets_en_attente' => Projet::where('statut_projet', 'en attente')->count(),
        'projets_annules' => Projet::where('statut_projet', 'annulé')->count(),
    ];
    
    
    // === 3. CHART: DISTRIBUTION DES STATUTS (Pie/Donut Chart) ===
    $statutsChart = [
        'labels' => ['En cours', 'Terminé', 'En attente', 'Annulé'],
        'data' => [
            $stats['projets_en_cours'],
            $stats['projets_termines'],
            $stats['projets_en_attente'],
            $stats['projets_annules']
        ],
        'colors' => ['#3B82F6', '#10B981', '#F59E0B', '#EF4444']
    ];
    
    
    // === 4. CHART: PROJETS PAR MOIS (Line/Bar Chart - 12 derniers mois) ===
    $projetsParMois = [];
    $labels = [];
    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $labels[] = $date->format('M Y');
        $projetsParMois[] = Projet::whereYear('created_at', $date->year)
                                   ->whereMonth('created_at', $date->month)
                                   ->count();
    }
    
    $projetsTimeline = [
        'labels' => $labels,
        'data' => $projetsParMois
    ];
    
    
    // === 5. CHART: AVANCEMENT MOYEN PAR PROJET (Top 10) ===
    $topProjetsAvancement = Projet::with('avancements')
        ->get()
        ->map(function($projet) {
            $pourcentage = $projet->avancements->sum('pourcentage');
            if ($pourcentage > 100) $pourcentage = 100;
            return [
                'titre' => \Str::limit($projet->titre, 20),
                'pourcentage' => $pourcentage
            ];
        })
        ->sortByDesc('pourcentage')
        ->take(10)
        ->values();
    
    $avancementChart = [
        'labels' => $topProjetsAvancement->pluck('titre')->toArray(),
        'data' => $topProjetsAvancement->pluck('pourcentage')->toArray()
    ];
    
    
    // === 6. CHART: RENDEZ-VOUS PAR STATUT ===
    $rdvStats = [
        'planifie' => \App\Models\RendezVous::where('statut', 'planifié')->count(),
        'confirme' => \App\Models\RendezVous::where('statut', 'confirmé')->count(),
        'termine' => \App\Models\RendezVous::where('statut', 'terminé')->count(),
        'annule' => \App\Models\RendezVous::where('statut', 'annulé')->count(),
    ];
    
    $rdvChart = [
        'labels' => ['Planifié', 'Confirmé', 'Terminé', 'Annulé'],
        'data' => array_values($rdvStats),
        'colors' => ['#6366F1', '#10B981', '#059669', '#DC2626']
    ];
    
    
    // === 7. RENDEZ-VOUS À VENIR (Prochains 7 jours) ===
    $rdvAVenir = \App\Models\RendezVous::with('projet')
        ->whereBetween('date_heure', [now(), now()->addDays(7)])
        ->orderBy('date_heure', 'asc')
        ->take(5)
        ->get();
    
    
    // === 8. PROJETS CRITIQUES (Date fin proche + statut en cours) ===
    $projetsCritiques = Projet::where('statut_projet', 'en cours')
        ->where('date_fin', '<=', now()->addDays(7))
        ->where('date_fin', '>=', now())
        ->with('users')
        ->orderBy('date_fin', 'asc')
        ->take(5)
        ->get();
    
    
    // === 9. DONNÉES POUR LES FILTRES (dropdowns) ===
    $clients = User::role('Client')->get();
    $statutsDisponibles = ['en cours', 'terminé', 'en attente', 'annulé'];
    
    
    // === 10. ACTIVITÉ RÉCENTE (Timeline) ===
    $activiteRecente = collect();
    
    // Derniers projets créés
    $derniersProjets = Projet::latest()->take(3)->get()->map(function($p) {
        return [
            'type' => 'projet',
            'action' => 'créé',
            'titre' => $p->titre,
            'date' => $p->created_at,
            'icon' => 'folder-plus'
        ];
    });
    
    // Derniers avancements
    $derniersAvancements = Avancement::with('projet')->latest()->take(3)->get()->map(function($a) {
        return [
            'type' => 'avancement',
            'action' => 'ajouté',
            'titre' => $a->titre . ' (' . $a->projet->titre . ')',
            'date' => $a->created_at,
            'icon' => 'trending-up'
        ];
    });
    
    $activiteRecente = $derniersProjets->concat($derniersAvancements)
                                       ->sortByDesc('date')
                                       ->take(6);
    
    
    return view('admin.projets.index', compact(
        'projets',
        'stats',
        'statutsChart',
        'projetsTimeline',
        'avancementChart',
        'rdvChart',
        'rdvAVenir',
        'projetsCritiques',
        'clients',
        'statutsDisponibles',
        'activiteRecente'
    ));
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


    public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les Projets li mamsou7in
    // KanLoadéw relation users bach yban l-assigné
    $projets = Projet::onlyTrashed()
                  ->with('users') 
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('admin.projets.corbeille', compact('projets'));
}

// N°2. Restauration d'un Projet (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-Projet b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $projet = Projet::withTrashed()->findOrFail($id);
    $projet->restore();

    return redirect()->route('admin.projets.corbeille')->with('success', 'Projet restauré avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-Projet b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $projet = Projet::withTrashed()->findOrFail($id);
    
    // ⚠️ Mola7aḍa: Ila 3endek des fichiers flouked (b7al `fichier`), khass tmass7hom hna 9bel Force Delete.
    // Storage::disk('public')->delete($projet->fichier);

    $projet->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('admin.projets.corbeille')->with('success', 'Projet supprimé définitivement!');
}
}
