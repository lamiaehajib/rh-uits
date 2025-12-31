<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectHistoricalAbsences extends Command
{
    protected $signature = 'absences:detect-historical {--from=} {--to=} {--user=}';
    protected $description = 'Détecter toutes les absences historiques (Exclut les clients et admins)';

    public function handle()
    {
        $premierPointage = SuivrePointage::min('date_pointage');
        
        $dateDebut = $this->option('from') 
            ? Carbon::parse($this->option('from'))
            : ($premierPointage ? Carbon::parse($premierPointage) : Carbon::now()->subMonths(3));
            
        $dateFin = $this->option('to')
            ? Carbon::parse($this->option('to'))
            : Carbon::yesterday('Africa/Casablanca');

        $this->info("🔍 Détection des absences du {$dateDebut->format('Y-m-d')} au {$dateFin->format('Y-m-d')}");
        
        $joursSemaine = [
            'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
        ];

        // جلب المستخدمين المستهدفين مع استثناء الأدوار الإدارية
        $usersQuery = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            });

        if ($userId = $this->option('user')) {
            $usersQuery->where('id', $userId);
        }

        $users = $usersQuery->get();
        $this->info("👥 {$users->count()} utilisateur(s) à traiter");

        $totalAbsences = 0;
        $progressBar = $this->output->createProgressBar($users->count());
        $progressBar->start();

        foreach ($users as $user) {
            $date = $dateDebut->copy();
            while ($date->lte($dateFin)) {
                $jourActuel = $joursSemaine[$date->englishDayOfWeek];
                $joursRepos = $user->repos ?? [];

                // شروط التخطي (يوم راحة أو وجود بصمة حضور أو غياب مسجل)
                $isRepos = in_array($jourActuel, $joursRepos);
                $hasPresence = SuivrePointage::where('iduser', $user->id)->whereDate('date_pointage', $date)->where('type', 'presence')->exists();
                $hasAbsence = SuivrePointage::where('iduser', $user->id)->whereDate('date_pointage', $date)->where('type', 'absence')->exists();

                if (!$isRepos && !$hasPresence && !$hasAbsence) {
                    SuivrePointage::create([
                        'iduser' => $user->id,
                        'date_pointage' => $date->copy(),
                        'type' => 'absence',
                        'description' => 'Absence historique détectée automatiquement',
                        'localisation' => 'N/A',
                        'heure_arrivee' => null,
                        'heure_depart' => null,
                    ]);
                    $totalAbsences++;
                }
                $date->addDay();
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\n✅ Terminé! {$totalAbsences} absences ajoutées.");
        return 0;
    }
}