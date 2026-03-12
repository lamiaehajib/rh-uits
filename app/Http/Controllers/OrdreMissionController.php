<?php

namespace App\Http\Controllers;

use App\Models\OrdreMission;
use App\Models\User;
use App\Notifications\OrdreMissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdreMissionController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $isAdmin = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);

        $query = $isAdmin
            ? OrdreMission::with(['employe', 'admin'])
            : OrdreMission::pourEmploye($user->id)->with('admin');

        if ($request->filled('statut'))    $query->where('statut', $request->statut);
        if ($request->filled('search'))    $query->where(function($q) use ($request) {
            $q->where('destination', 'like', '%'.$request->search.'%')
              ->orWhere('objet', 'like', '%'.$request->search.'%');
        });
        if ($request->filled('date_from')) $query->where('date_depart', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->where('date_depart', '<=', $request->date_to . ' 23:59:59');

        $missions = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'      => $isAdmin ? OrdreMission::count()               : OrdreMission::pourEmploye($user->id)->count(),
            'en_attente' => $isAdmin ? OrdreMission::enAttente()->count()   : OrdreMission::pourEmploye($user->id)->enAttente()->count(),
            'approuve'   => $isAdmin ? OrdreMission::approuve()->count()    : OrdreMission::pourEmploye($user->id)->approuve()->count(),
            'refuse'     => $isAdmin ? OrdreMission::refuse()->count()      : OrdreMission::pourEmploye($user->id)->refuse()->count(),
        ];

        return view('ordre_missions.index', compact('missions', 'stats', 'isAdmin'));
    }

    public function create()
    {
        return view('ordre_missions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination'           => 'required|string|max:255',
            'objet'                 => 'required|string|max:500',
            'date_depart'           => 'required|date|after_or_equal:now',
            'date_retour'           => 'required|date|after_or_equal:date_depart',
            'moyen_transport'       => 'required|in:voiture_personnelle,train,avion,bus,autre',
            'moyen_transport_autre' => 'nullable|string|max:255',
            'frais_transport'       => 'required|numeric|min:0',
            'frais_hebergement'     => 'required|numeric|min:0',
            'frais_repas'           => 'required|numeric|min:0',
            'frais_divers'          => 'nullable|numeric|min:0',
            'avance_demandee'       => 'required|numeric|min:0',
            'notes_employe'         => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['statut']  = 'en_attente';

        $mission = OrdreMission::create($validated);

        $admins = User::role(['Sup_Admin', 'Custom_Admin'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new OrdreMissionNotification($mission, 'nouvelle_demande'));
        }

        return redirect()->route('ordre-missions.show', $mission)
                         ->with('success', 'Votre demande d\'ordre de mission a été soumise avec succès.');
    }

    public function show(OrdreMission $ordreMission)
    {
        $user    = Auth::user();
        $isAdmin = $user->hasAnyRole(['Sup_Admin', 'Custom_Admin']);

        if (!$isAdmin && $ordreMission->user_id !== $user->id) abort(403);

        $ordreMission->load(['employe', 'admin', 'justificatifs.uploader']);
        return view('ordre_missions.show', compact('ordreMission', 'isAdmin'));
    }

    public function edit(OrdreMission $ordreMission)
    {
        $user = Auth::user();
        if ($ordreMission->user_id !== $user->id || $ordreMission->statut !== 'en_attente') {
            abort(403, 'Modification impossible.');
        }
        return view('ordre_missions.edit', compact('ordreMission'));
    }

    public function update(Request $request, OrdreMission $ordreMission)
    {
        $user = Auth::user();
        if ($ordreMission->user_id !== $user->id || $ordreMission->statut !== 'en_attente') abort(403);

        $validated = $request->validate([
            'destination'           => 'required|string|max:255',
            'objet'                 => 'required|string|max:500',
            'date_depart'           => 'required|date',
            'date_retour'           => 'required|date|after_or_equal:date_depart',
            'moyen_transport'       => 'required|in:voiture_personnelle,train,avion,bus,autre',
            'moyen_transport_autre' => 'nullable|string|max:255',
            'frais_transport'       => 'required|numeric|min:0',
            'frais_hebergement'     => 'required|numeric|min:0',
            'frais_repas'           => 'required|numeric|min:0',
            'frais_divers'          => 'nullable|numeric|min:0',
            'avance_demandee'       => 'required|numeric|min:0',
            'notes_employe'         => 'nullable|string|max:1000',
        ]);

        $ordreMission->update($validated);
        return redirect()->route('ordre-missions.show', $ordreMission)->with('success', 'Demande mise à jour.');
    }

    public function annuler(OrdreMission $ordreMission)
    {
        $user = Auth::user();
        if ($ordreMission->user_id !== $user->id) abort(403);
        if (!in_array($ordreMission->statut, ['en_attente', 'approuve'])) {
            return back()->with('error', 'Cette demande ne peut plus être annulée.');
        }
        $ordreMission->update(['statut' => 'annule']);
        return back()->with('success', 'Demande annulée.');
    }

    public function approuver(Request $request, OrdreMission $ordreMission)
    {
        $request->validate([
            'commentaire_admin' => 'nullable|string|max:1000',
            'avance_versee'     => 'nullable|numeric|min:0',
        ]);
        $ordreMission->update([
            'statut'            => 'approuve',
            'traite_par'        => Auth::id(),
            'date_traitement'   => now(),
            'commentaire_admin' => $request->commentaire_admin,
            'avance_versee'     => $request->avance_versee ?? $ordreMission->avance_demandee,
        ]);
        $ordreMission->employe->notify(new OrdreMissionNotification($ordreMission, 'approuve'));
        return back()->with('success', 'Mission approuvée avec succès.');
    }

    public function refuser(Request $request, OrdreMission $ordreMission)
    {
        $request->validate(['motif_refus' => 'required|string|max:1000']);
        $ordreMission->update([
            'statut'          => 'refuse',
            'traite_par'      => Auth::id(),
            'date_traitement' => now(),
            'motif_refus'     => $request->motif_refus,
        ]);
        $ordreMission->employe->notify(new OrdreMissionNotification($ordreMission, 'refuse'));
        return back()->with('success', 'Mission refusée.');
    }

    public function cloturer(Request $request, OrdreMission $ordreMission)
    {
        $request->validate([
            'frais_reels'     => 'required|numeric|min:0',
            'solde_rembourse' => 'nullable|numeric',
        ]);
        $solde = $request->solde_rembourse ?? ($request->frais_reels - ($ordreMission->avance_versee ?? 0));
        $ordreMission->update([
            'statut'          => 'cloture',
            'frais_reels'     => $request->frais_reels,
            'solde_rembourse' => $solde,
            'date_cloture'    => now(),
        ]);
        return back()->with('success', 'Mission clôturée. Solde : ' . number_format($solde, 2) . ' MAD');
    }

    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['Sup_Admin', 'Custom_Admin'])) abort(403);

        $stats = [
            'en_attente'        => OrdreMission::enAttente()->count(),
            'approuve_ce_mois'  => OrdreMission::approuve()->whereMonth('date_traitement', now()->month)->count(),
            'budget_total_mois' => OrdreMission::approuve()->whereMonth('date_depart', now()->month)->sum('avance_versee'),
            'top_destinations'  => OrdreMission::select('destination', DB::raw('count(*) as total'))
                                      ->groupBy('destination')->orderByDesc('total')->limit(5)->get(),
        ];
        $missionsEnCours = OrdreMission::enAttente()->with('employe')->latest()->get();

        return view('ordre_missions.dashboard', compact('stats', 'missionsEnCours'));
    }
}