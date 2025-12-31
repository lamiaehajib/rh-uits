<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectHistoricalAbsences extends Command
{
    protected $signature = 'absences:detect-historical {--from=} {--to=}';

    public function handle()
    {
        $dateDebut = $this->option('from') ? Carbon::parse($this->option('from')) : Carbon::now()->subMonths(1);
        $dateFin = $this->option('to') ? Carbon::parse($this->option('to')) : Carbon::yesterday();

        $users = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            })
            ->whereHas('pointages', function ($query) {
                $query->where('type', 'presence');
            })
            ->get();

        foreach ($users as $user) {
            $date = $dateDebut->copy();
            while ($date->lte($dateFin)) {
                $jour = Carbon::parse($date)->format('l');
                $joursSemaine = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'];
                $jourActuel = $joursSemaine[$jour];

                $isRepos = in_array($jourActuel, $user->repos ?? []);
                $hasPresence = SuivrePointage::where('iduser', $user->id)->whereDate('date_pointage', $date)->where('type', 'presence')->exists();
                $hasAbsence = SuivrePointage::where('iduser', $user->id)->whereDate('date_pointage', $date)->where('type', 'absence')->exists();

                if (!$isRepos && !$hasPresence && !$hasAbsence) {
                    SuivrePointage::create([
                        'iduser' => $user->id,
                        'date_pointage' => $date->copy(),
                        'type' => 'absence',
                        'description' => 'Absence historique détectée',
                    ]);
                }
                $date->addDay();
            }
        }
        $this->info("✅ التاريخي انتهى بنجاح.");
    }
}