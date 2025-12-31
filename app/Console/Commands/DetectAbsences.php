<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use App\Models\Conge;
use Carbon\Carbon;

class DetectAbsences extends Command
{
    protected $signature = 'absences:daily {--date=}';
    protected $description = 'Détecter les absences (Exclut les admins, congés approuvés, et users non-enregistrés)';

    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday('Africa/Casablanca');
        $this->info("🔍 Analyse pour le : {$date->format('Y-m-d')}");

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
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            })
            ->whereHas('suiviPointages', function ($query) {
                $query->where('type', 'presence');
            })
            ->get();

        foreach ($users as $user) {
            // 1️⃣ Check repos
            $joursRepos = $user->repos ?? [];
            if (in_array($jourActuel, $joursRepos)) {
                $this->info("⏭️  {$user->name} - Jour de repos");
                continue;
            }

            // 2️⃣ Check si déjà présence
            $pointageExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'presence')
                ->exists();

            if ($pointageExiste) {
                $this->info("✅ {$user->name} - Présent");
                continue;
            }

            // 3️⃣ **NOUVEAU: Check si en congé approuvé**
            $enConge = Conge::where('user_id', $user->id)
                ->where('statut', 'approuve')
                ->whereDate('date_debut', '<=', $date)
                ->whereDate('date_fin', '>=', $date)
                ->exists();

            // 4️⃣ Check si déjà enregistré (absence ou congé)
            $enregistrementExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->whereIn('type', ['absence', 'conge'])
                ->exists();

            if ($enregistrementExiste) {
                $this->info("⏭️  {$user->name} - Déjà enregistré");
                continue;
            }

            // 5️⃣ Créer l'enregistrement (congé ou absence)
            if ($enConge) {
                SuivrePointage::create([
                    'iduser'        => $user->id,
                    'date_pointage' => $date,
                    'type'          => 'conge',
                    'description'   => 'Congé approuvé (Système)',
                ]);
                $this->line("🏖️  {$user->name} - EN CONGÉ");
            } else {
                SuivrePointage::create([
                    'iduser'        => $user->id,
                    'date_pointage' => $date,
                    'type'          => 'absence',
                    'description'   => 'Absence détectée automatiquement (Système)',
                ]);
                $this->error("❌ {$user->name} - ABSENCE ENREGISTRÉE");
            }
        }

        $this->info("✅ Analyse terminée !");
        return 0;
    }
}