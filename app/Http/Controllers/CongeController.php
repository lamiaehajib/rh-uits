<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\SoldeConge;
use App\Services\CongeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CongeController extends Controller
{
    protected $congeService;

    public function __construct(CongeService $congeService)
    {
        $this->congeService = $congeService;
    }

    /**
     * Liste des congés (pour l'utilisateur connecté ou tous pour les admins)
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole(['Custom_Admin', 'Sup_Admin'])) {
            $conges = Conge::with(['user', 'traitePar'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            $conges = Conge::where('user_id', $user->id)
                ->with('traitePar')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }
        
        $anneeActuelle = date('Y');
        $solde = SoldeConge::initSolde($user->id, $anneeActuelle);
        
        return view('conges.index', compact('conges', 'solde'));
    }

    /**
     * Formulaire de demande de congé
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->hasRole('Client')) {
            return redirect()->route('conges.index')
                ->with('error', 'Les clients ne peuvent pas demander de congés.');
        }
        
        $anneeActuelle = date('Y');
        $solde = SoldeConge::initSolde($user->id, $anneeActuelle);
        
        return view('conges.create', compact('solde'));
    }

    /**
     * Prévisualiser le calcul des jours
     */
    public function previewCalcul(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);
        
        $user = Auth::user();
        $details = $this->congeService->getDetailJours(
            $request->date_debut,
            $request->date_fin,
            $user
        );
        
        return response()->json($details);
    }

    /**
     * Enregistrer une demande de congé
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasRole('Client')) {
            return redirect()->route('conges.index')
                ->with('error', 'Les clients ne peuvent pas demander de congés.');
        }
        
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string|max:500'
        ]);
        
        // Calculer le nombre de jours (TOUS les jours sont comptés)
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = Carbon::parse($request->date_fin);
        $nombreJours = $dateDebut->diffInDays($dateFin) + 1;
        
        if ($nombreJours <= 0) {
            return redirect()->back()
                ->with('error', 'La période sélectionnée est invalide.')
                ->withInput();
        }
        
        // Vérifier le solde disponible
        $annee = $dateDebut->year;
        if (!$this->congeService->peutPrendreConge($user, $nombreJours, $annee)) {
            $solde = SoldeConge::initSolde($user->id, $annee);
            return redirect()->back()
                ->with('error', "Solde insuffisant. Vous avez {$solde->jours_restants} jour(s) disponible(s).")
                ->withInput();
        }
        
        // Créer la demande de congé
        Conge::create([
            'user_id' => $user->id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'nombre_jours_demandes' => $nombreJours,
            'nombre_jours_ouvrables' => $nombreJours, // Même valeur maintenant
            'motif' => $request->motif,
            'statut' => 'en_attente'
        ]);
        
        return redirect()->route('conges.index')
            ->with('success', 'Votre demande de congé a été soumise avec succès.');
    }

    /**
     * Afficher les détails d'un congé
     */
    public function show(Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->hasRole(['Custom_Admin', 'Sup_Admin']) && $conge->user_id != $user->id) {
            abort(403, 'Action non autorisée.');
        }
        
        $details = $this->congeService->getDetailJours(
            $conge->date_debut,
            $conge->date_fin,
            $conge->user
        );
        
        return view('conges.show', compact('conge', 'details'));
    }

    /**
     * Approuver un congé (réservé aux admins)
     */
    public function approve(Request $request, Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->hasRole(['Custom_Admin', 'Sup_Admin'])) {
            abort(403, 'Action non autorisée.');
        }
        
        if ($conge->statut != 'en_attente') {
            return redirect()->back()
                ->with('error', 'Ce congé a déjà été traité.');
        }
        
        $conge->update([
            'statut' => 'approuve',
            'commentaire_admin' => $request->commentaire,
            'traite_par' => $user->id,
            'traite_le' => now()
        ]);
        
        // Déduire les jours du solde (utilise nombre_jours_demandes)
        $annee = $conge->date_debut->year;
        $solde = SoldeConge::initSolde($conge->user_id, $annee);
        $solde->utiliserJours($conge->nombre_jours_demandes);
        
        return redirect()->route('conges.show', $conge)
            ->with('success', 'Le congé a été approuvé.');
    }

    /**
     * Refuser un congé (réservé aux admins)
     */
    public function reject(Request $request, Conge $conge)
    {
        $user = Auth::user();
        
        if (!$user->hasRole(['Custom_Admin', 'Sup_Admin'])) {
            abort(403, 'Action non autorisée.');
        }
        
        if ($conge->statut != 'en_attente') {
            return redirect()->back()
                ->with('error', 'Ce congé a déjà été traité.');
        }
        
        $request->validate([
            'commentaire' => 'required|string|max:500'
        ]);
        
        $conge->update([
            'statut' => 'refuse',
            'commentaire_admin' => $request->commentaire,
            'traite_par' => $user->id,
            'traite_le' => now()
        ]);
        
        return redirect()->route('conges.show', $conge)
            ->with('success', 'Le congé a été refusé.');
    }

    /**
     * Afficher le solde de congés de l'utilisateur
     */
    public function solde()
    {
        $user = Auth::user();
        $anneeActuelle = date('Y');
        $solde = SoldeConge::initSolde($user->id, $anneeActuelle);
        
        $congesApprouves = Conge::where('user_id', $user->id)
            ->where('statut', 'approuve')
            ->whereYear('date_debut', $anneeActuelle)
            ->get();
        
        return view('conges.solde', compact('solde', 'congesApprouves'));
    }
}