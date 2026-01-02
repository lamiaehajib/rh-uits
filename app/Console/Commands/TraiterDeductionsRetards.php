<?php

namespace App\Console\Commands;

use App\Services\RetardCongeService;
use Illuminate\Console\Command;

class TraiterDeductionsRetards extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'retards:traiter-deductions 
                            {--mois= : Mois à traiter (optionnel)}
                            {--annee= : Année à traiter (optionnel)}';

    /**
     * The console command description.
     */
    protected $description = 'Traiter les déductions de congés basées sur les retards mensuels';

    protected $retardService;

    public function __construct(RetardCongeService $retardService)
    {
        parent::__construct();
        $this->retardService = $retardService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Traitement des déductions de congés pour retards...');
        
        $resultat = $this->retardService->traiterDeductionsMensuelles();
        
        if ($resultat['success']) {
            $this->info('✅ Traitement terminé avec succès!');
            
            if (!empty($resultat['deductions'])) {
                $this->info("\n📊 Résumé des déductions:");
                
                $this->table(
                    ['Utilisateur', 'Minutes Retard', 'Jours Déduits', 'Nouveau Solde'],
                    collect($resultat['deductions'])->map(function($d) {
                        return [
                            $d['user_name'],
                            $d['minutes_retard'],
                            $d['jours_deduits'],
                            $d['nouveau_solde']
                        ];
                    })
                );
                
                $totalJoursDeduits = collect($resultat['deductions'])->sum('jours_deduits');
                $this->info("\n📉 Total de jours déduits: {$totalJoursDeduits}");
            } else {
                $this->info("ℹ️  Aucune déduction à effectuer ce mois-ci.");
            }
        } else {
            $this->error('❌ Erreur lors du traitement: ' . $resultat['error']);
            return 1;
        }
        
        return 0;
    }
}