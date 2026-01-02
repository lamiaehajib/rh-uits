<?php

namespace App\Http\Controllers;

use App\Models\SuivrePointage;
use App\Models\User;
use App\Services\RetardCongeService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RetardCongeController extends Controller
{
    protected $retardService;

    public function __construct(RetardCongeService $retardService)
    {
        $this->retardService = $retardService;
    }

    /**
     * Afficher le rapport des retards pour l'utilisateur connecté
     */
    public function monRapport(Request $request)
    {
        $user = auth()->user();
        $mois = $request->input('mois', Carbon::now()->month);
        $annee = $request->input('annee', Carbon::now()->year);
        
        $rapport = $this->retardService->rapportRetardsUtilisateur($user->id, $mois, $annee);
        
        return view('retards.rapport', compact('rapport', 'mois', 'annee'));
    }

    /**
     * Afficher le tableau de bord admin des retards
     */
    public function dashboardAdmin(Request $request)
    {
        if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        $mois = $request->input('mois', Carbon::now()->month);
        $annee = $request->input('annee', Carbon::now()->year);

        // Récupérer tous les utilisateurs avec leurs stats de retard
        $utilisateurs = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'client');
        })->get()->map(function($user) use ($mois, $annee) {
            $stats = $this->retardService->calculerRetardMensuel($user->id, $mois, $annee);
            return [
                'user' => $user,
                'stats' => $stats,
                'alerte' => $this->retardService->verifierAlerteRetard($user->id)
            ];
        })->sortByDesc('stats.total_minutes');

        // Retards en attente de validation
        $retardsEnAttente = $this->retardService->getRetardsEnAttenteValidation();

        return view('retards.dashboard-admin', compact('utilisateurs', 'retardsEnAttente', 'mois', 'annee'));
    }

    /**
     * Vérifier l'alerte retard (API pour affichage dynamique)
     */
    public function checkAlerte()
    {
        $user = auth()->user();
        $alerte = $this->retardService->verifierAlerteRetard($user->id);
        
        return response()->json($alerte);
    }

    /**
     * Exécuter manuellement les déductions (Admin uniquement)
     */
    public function executerDeductions(Request $request)
    {
        if (!auth()->user()->hasRole(['Sup_Admin', 'Custom_Admin'])) {
            abort(403, 'Accès non autorisé.');
        }

        $resultat = $this->retardService->traiterDeductionsMensuelles();
        
        if ($resultat['success']) {
            return redirect()->back()->with('success', 
                'Déductions traitées avec succès. ' . 
                count($resultat['deductions']) . ' utilisateur(s) affecté(s).'
            );
        } else {
            return redirect()->back()->with('error', 
                'Erreur lors du traitement: ' . $resultat['error']
            );
        }
    }
}

