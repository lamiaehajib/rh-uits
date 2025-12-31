<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectAbsences extends Command
{
 protected $signature = 'absences:daily {--date=}';
    protected $description = 'Détecter les absences quotidiennes (Exclut les clients)';

    public function handle()
    {
        $date = $this->option('date') 
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday('Africa/Casablanca');

        $this->info("🔍 Détection des absences pour: {$date->format('Y-m-d')}");

        $joursSemaine = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi', 
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];
        
        $jourActuel = $joursSemaine[$date->englishDayOfWeek];

        $users = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'client');
            })
            ->get();

        $absencesDetectees = 0;

        foreach ($users as $user) {
            // Vérifier jour de repos
            $joursRepos = $user->repos ?? [];
            if (in_array($jourActuel, $joursRepos)) {
                $this->line("⏭️  {$user->name} - Jour de repos ({$jourActuel})");
                continue;
            }

            // Vérifier présence
            $pointageExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'presence')
                ->exists();

            if ($pointageExiste) {
                $this->line("✅ {$user->name} - Présent");
                continue;
            }

            // Vérifier si absence déjà enregistrée
            $absenceExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'absence')
                ->exists();

            if (!$absenceExiste) {
                SuivrePointage::create([
                    'iduser' => $user->id,
                    'date_pointage' => $date,
                    'type' => 'absence',
                    'description' => 'Absence détectée automatiquement',
                    'localisation' => 'N/A',
                    'heure_arrivee' => null,
                    'heure_depart' => null,
                ]);

                $this->error("❌ {$user->name} - ABSENCE ENREGISTRÉE");
                $absencesDetectees++;
            } else {
                $this->line("⚠️  {$user->name} - Absence déjà enregistrée");
            }
        }

        $this->info("✅ Détection terminée: {$absencesDetectees} absence(s) enregistrée(s)");
        return 0;
    }
}