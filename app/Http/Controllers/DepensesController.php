<?php

namespace App\Http\Controllers;

use App\Models\DepenseFixe;
use App\Models\DepenseVariable;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepensesController extends Controller
{
    // Dashboard principal - Vue d'ensemble
    public function index(Request $request)
    {
        $moisActuel = $request->get('mois', Carbon::now()->format('Y-m'));
        
        // Total depenses fixes
        $totalFixe = DepenseFixe::parMois($moisActuel)
            ->where('statut', 'payé')
            ->sum('montant');
            
        // Total depenses variables
        $totalVariable = DepenseVariable::parMois($moisActuel)->sum('montant');
        
        // Total général
        $totalGeneral = $totalFixe + $totalVariable;
        
        // Détails par catégorie pour depenses fixes
        $depensesFixesDetail = DepenseFixe::parMois($moisActuel)
            ->selectRaw('type, SUM(montant) as total')
            ->groupBy('type')
            ->get();
            
        // Détails par catégorie pour depenses variables
        $depensesVariablesDetail = DepenseVariable::parMois($moisActuel)
            ->selectRaw('categorie, SUM(montant) as total')
            ->groupBy('categorie')
            ->get();
        
        // Dernières dépenses
        $dernieresDepenses = [
            'fixes' => DepenseFixe::parMois($moisActuel)->latest()->take(5)->get(),
            'variables' => DepenseVariable::parMois($moisActuel)->latest()->take(5)->get()
        ];
        
        // Évolution sur 6 mois
        $evolutionMensuelle = [];
        for ($i = 5; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i)->format('Y-m');
            $evolutionMensuelle[$mois] = [
                'fixe' => DepenseFixe::parMois($mois)->where('statut', 'payé')->sum('montant'),
                'variable' => DepenseVariable::parMois($mois)->sum('montant')
            ];
        }
        
        return view('depenses.index', compact(
            'moisActuel',
            'totalFixe',
            'totalVariable',
            'totalGeneral',
            'depensesFixesDetail',
            'depensesVariablesDetail',
            'dernieresDepenses',
            'evolutionMensuelle'
        ));
    }
    
    // Liste des dépenses fixes
    public function depensesFixes(Request $request)
    {
        $mois = $request->get('mois', Carbon::now()->format('Y-m'));
        $statut = $request->get('statut');
        
        $query = DepenseFixe::with(['user', 'salarie'])->parMois($mois);
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        $depenses = $query->latest('date_depense')->paginate(20);
        $total = $query->sum('montant');
        
        // Récupérer tous les users avec leurs salaires
        $salaries = User::whereNotNull('salaire')
            ->where('salaire', '>', 0)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('depenses.fixes.index', compact('depenses', 'total', 'mois', 'salaries'));
    }
    
    // Créer dépense fixe
    public function storeDepenseFixe(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'statut' => 'required|in:payé,en_attente,annulé',
            'notes' => 'nullable|string',
            'salarie_id' => 'nullable|exists:users,id'
        ]);
        
        $validated['mois'] = Carbon::parse($validated['date_depense'])->format('Y-m');
        $validated['user_id'] = auth()->id();
        
        DepenseFixe::create($validated);
        
        return redirect()->back()->with('success', 'Dépense fixe ajoutée avec succès!');
    }
    
    // Mettre à jour dépense fixe
    public function updateDepenseFixe(Request $request, DepenseFixe $depense)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'statut' => 'required|in:payé,en_attente,annulé',
            'notes' => 'nullable|string',
            'salarie_id' => 'nullable|exists:users,id'
        ]);
        
        $validated['mois'] = Carbon::parse($validated['date_depense'])->format('Y-m');
        
        $depense->update($validated);
        
        return redirect()->back()->with('success', 'Dépense fixe modifiée avec succès!');
    }
    
    // Supprimer dépense fixe
    public function destroyDepenseFixe(DepenseFixe $depense)
    {
        $depense->delete();
        return redirect()->back()->with('success', 'Dépense fixe supprimée avec succès!');
    }
    
    // Générer salaires automatiquement pour le mois
    public function genererSalaires(Request $request)
    {
        $mois = $request->get('mois', Carbon::now()->format('Y-m'));
        $date = Carbon::parse($mois . '-01');
        
        // Récupérer tous les users actifs avec salaire
        $salaries = User::whereNotNull('salaire')
            ->where('salaire', '>', 0)
            ->where('is_active', true)
            ->get();
        
        $count = 0;
        foreach ($salaries as $salarie) {
            // Vérifier si le salaire existe déjà pour ce mois
            $exists = DepenseFixe::where('salarie_id', $salarie->id)
                ->where('mois', $mois)
                ->where('type', 'SALAIRE')
                ->exists();
            
            if (!$exists) {
                DepenseFixe::create([
                    'type' => 'SALAIRE',
                    'description' => 'Salaire de ' . $salarie->name,
                    'montant' => $salarie->salaire,
                    'date_depense' => $date->lastOfMonth(),
                    'mois' => $mois,
                    'statut' => 'en_attente',
                    'notes' => 'Généré automatiquement',
                    'user_id' => auth()->id(),
                    'salarie_id' => $salarie->id
                ]);
                $count++;
            }
        }
        
        return redirect()->back()->with('success', "$count salaire(s) généré(s) avec succès pour $mois!");
    }
    
    // Liste des dépenses variables
    public function depensesVariables(Request $request)
    {
        $mois = $request->get('mois', Carbon::now()->format('Y-m'));
        $categorie = $request->get('categorie');
        
        $query = DepenseVariable::with(['user', 'beneficiaire'])->parMois($mois);
        
        if ($categorie) {
            $query->where('categorie', $categorie);
        }
        
        $depenses = $query->latest('date_depense')->paginate(20);
        $total = $query->sum('montant');
        
        $users = User::all();
        
        return view('depenses.variables.index', compact('depenses', 'total', 'mois', 'users'));
    }
    
    // Créer dépense variable
    public function storeDepenseVariable(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'categorie' => 'required|in:primes_repos,achats_equipements,produits_menages,frais_bancaires,publications,autres',
            'notes' => 'nullable|string',
            'beneficiaire_id' => 'nullable|exists:users,id',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);
        
        $validated['mois'] = Carbon::parse($validated['date_depense'])->format('Y-m');
        $validated['user_id'] = auth()->id();
        
        if ($request->hasFile('justificatif')) {
            $validated['justificatif'] = $request->file('justificatif')
                ->store('justificatifs', 'public');
        }
        
        DepenseVariable::create($validated);
        
        return redirect()->back()->with('success', 'Dépense variable ajoutée avec succès!');
    }
    
    // Mettre à jour dépense variable
    public function updateDepenseVariable(Request $request, DepenseVariable $depense)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'categorie' => 'required|in:primes_repos,achats_equipements,produits_menages,frais_bancaires,publications,autres',
            'notes' => 'nullable|string',
            'beneficiaire_id' => 'nullable|exists:users,id',
            'justificatif' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);
        
        $validated['mois'] = Carbon::parse($validated['date_depense'])->format('Y-m');
        
        if ($request->hasFile('justificatif')) {
            $validated['justificatif'] = $request->file('justificatif')
                ->store('justificatifs', 'public');
        }
        
        $depense->update($validated);
        
        return redirect()->back()->with('success', 'Dépense variable modifiée avec succès!');
    }
    
    // Supprimer dépense variable
    public function destroyDepenseVariable(DepenseVariable $depense)
    {
        $depense->delete();
        return redirect()->back()->with('success', 'Dépense variable supprimée avec succès!');
    }
    
    // Générer rapport mensuel
    public function rapportMensuel(Request $request)
    {
        $mois = $request->get('mois', Carbon::now()->format('Y-m'));
        
        $rapportFixe = DepenseFixe::parMois($mois)
            ->selectRaw('type, COUNT(*) as nombre, SUM(montant) as total, statut')
            ->groupBy('type', 'statut')
            ->get();
            
        $rapportVariable = DepenseVariable::parMois($mois)
            ->selectRaw('categorie, COUNT(*) as nombre, SUM(montant) as total')
            ->groupBy('categorie')
            ->get();
        
        return view('depenses.rapport', compact('mois', 'rapportFixe', 'rapportVariable'));
    }
}