<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DetectHistoricalAbsences extends Command
{
    protected $signature = 'absences:detect-historical {--from=} {--to=} {--user=}';
    protected $description = 'Détecter toutes les absences historiques (Exclut les clients)';

    public function handle()
    {
        // Dates par défaut: depuis le premier pointage jusqu'à hier
        $premierPointage = SuivrePointage::min('date_pointage');
        
        $dateDebut = $this->option('from') 
            ? Carbon::parse($this->option('from'))
            : ($premierPointage ? Carbon::parse($premierPointage) : Carbon::now()->subMonths(3));
            
        $dateFin = $this->option('to')
            ? Carbon::parse($this->option('to'))
            : Carbon::yesterday('Africa/Casablanca');

        $this->info("🔍 Détection des absences du {$dateDebut->format('Y-m-d')} au {$dateFin->format('Y-m-d')}");
        
        // Mapping des jours
        $joursSemaine = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi', 
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];

        // Récupérer les utilisateurs (sans les clients)
        $usersQuery = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'client');
            });

        // Si option --user spécifiée
        if ($userId = $this->option('user')) {
            $usersQuery->where('id', $userId);
        }

        $users = $usersQuery->get();
        $this->info("👥 {$users->count()} utilisateur(s) à traiter");

        $totalAbsences = 0;
        $totalJours = 0;
        
        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        foreach ($users as $user) {
            $absencesUser = 0;
            
            // Pour chaque jour dans l'intervalle
            $date = $dateDebut->copy();
            while ($date->lte($dateFin)) {
                $totalJours++;
                
                // Déterminer le jour de la semaine
                $jourActuel = $joursSemaine[$date->englishDayOfWeek];
                
                // Vérifier si c'est un jour de repos
                $joursRepos = $user->repos ?? [];
                if (in_array($jourActuel, $joursRepos)) {
                    $date->addDay();
                    continue;
                }

                // Vérifier s'il y a un pointage (présence)
                $pointageExiste = SuivrePointage::where('iduser', $user->id)
                    ->whereDate('date_pointage', $date)
                    ->where('type', 'presence')
                    ->exists();

                if ($pointageExiste) {
                    $date->addDay();
                    continue;
                }

                // Vérifier si l'absence existe déjà
                $absenceExiste = SuivrePointage::where('iduser', $user->id)
                    ->whereDate('date_pointage', $date)
                    ->where('type', 'absence')
                    ->exists();

                if (!$absenceExiste) {
                    // Créer l'absence
                    SuivrePointage::create([
                        'iduser' => $user->id,
                        'date_pointage' => $date->copy(),
                        'type' => 'absence',
                        'description' => 'Absence détectée automatiquement (traitement historique)',
                        'localisation' => 'N/A',
                        'heure_arrivee' => null,
                        'heure_depart' => null,
                    ]);
                    
                    $absencesUser++;
                    $totalAbsences++;
                }

                $date->addDay();
            }
            
            if ($absencesUser > 0) {
                $this->newLine();
                $this->warn("⚠️  {$user->name}: {$absencesUser} absence(s) enregistrée(s)");
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Traitement terminé!");
        $this->info("📊 Statistiques:");
        $this->info("   - Utilisateurs traités: {$users->count()}");
        $this->info("   - Jours analysés: {$totalJours}");
        $this->info("   - Absences enregistrées: {$totalAbsences}");
        
        return 0;
    }
}