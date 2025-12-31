<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectAbsences extends Command
{
    protected $signature = 'absences:daily {--date=}';
    protected $description = 'Détecter les absences (Exclut les admins et les users non-enregistrés en machine)';

    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday('Africa/Casablanca');
        $this->info("🔍 Analyse pour le : {$date->format('Y-m-d')}");

        $joursSemaine = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'];
        $jourActuel = $joursSemaine[$date->englishDayOfWeek];

        $users = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            })
            // 💡 الشرط السحري: جلب الموظفين الذين بصموا مرة واحدة على الأقل في حياتهم
->whereHas('suiviPointages', function ($query) {
                    $query->where('type', 'presence');
            })
            ->get();

        foreach ($users as $user) {
            $joursRepos = $user->repos ?? [];
            if (in_array($jourActuel, $joursRepos)) continue;

            $pointageExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'presence')
                ->exists();

            if ($pointageExiste) continue;

            $absenceExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'absence')
                ->exists();

            if (!$absenceExiste) {
                SuivrePointage::create([
                    'iduser'        => $user->id,
                    'date_pointage' => $date,
                    'type'          => 'absence',
                    'description'   => 'Absence détectée automatiquement (Système)',
                ]);
                $this->error("❌ {$user->name} - ABSENCE ENREGISTRÉE");
            }
        }
        return 0;
    }
}