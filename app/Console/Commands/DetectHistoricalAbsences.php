<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use App\Models\Conge;
use Carbon\Carbon;

class DetectHistoricalAbsences extends Command
{
    protected $signature = 'absences:detect-historical {--from=} {--to=}';
    protected $description = 'Détecter les absences historiques (Exclut congés approuvés)';

    public function handle()
    {
        $dateDebut = $this->option('from') ? Carbon::parse($this->option('from')) : Carbon::now()->subMonths(1);
        $dateFin = $this->option('to') ? Carbon::parse($this->option('to')) : Carbon::yesterday();

        $this->info("📅 Période: {$dateDebut->format('Y-m-d')} → {$dateFin->format('Y-m-d')}");

        $users = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            })
            ->whereHas('suiviPointages', function ($query) {
                $query->where('type', 'presence');
            })
            ->get();

        $joursSemaine = [
            'Monday' => 'Lundi', 
            'Tuesday' => 'Mardi', 
            'Wednesday' => 'Mercredi', 
            'Thursday' => 'Jeudi', 
            'Friday' => 'Vendredi', 
            'Saturday' => 'Samedi', 
            'Sunday' => 'Dimanche'
        ];

        foreach ($users as $user) {
            $this->info("👤 Traitement: {$user->name}");
            $date = $dateDebut->copy();

            while ($date->lte($dateFin)) {
                $jour = $date->format('l');
                $jourActuel = $joursSemaine[$jour];

                // 1️⃣ Check repos
                $isRepos = in_array($jourActuel, $user->repos ?? []);
                if ($isRepos) {
                    $date->addDay();
                    continue;
                }

                // 2️⃣ Check présence
                $hasPresence = SuivrePointage::where('iduser', $user->id)
                    ->whereDate('date_pointage', $date)
                    ->where('type', 'presence')
                    ->exists();

                if ($hasPresence) {
                    $date->addDay();
                    continue;
                }

                // 3️⃣ **NOUVEAU: Check congé approuvé**
                $enConge = Conge::where('user_id', $user->id)
                    ->where('statut', 'approuve')
                    ->whereDate('date_debut', '<=', $date)
                    ->whereDate('date_fin', '>=', $date)
                    ->exists();

                // 4️⃣ Check si déjà enregistré
                $hasRecord = SuivrePointage::where('iduser', $user->id)
                    ->whereDate('date_pointage', $date)
                    ->whereIn('type', ['absence', 'conge'])
                    ->exists();

                if ($hasRecord) {
                    $date->addDay();
                    continue;
                }

                // 5️⃣ Créer l'enregistrement
                if ($enConge) {
                    SuivrePointage::create([
                        'iduser'        => $user->id,
                        'date_pointage' => $date->copy(),
                        'type'          => 'conge',
                        'description'   => 'Congé approuvé (Détection historique)',
                    ]);
                    $this->line("   🏖️  {$date->format('Y-m-d')} - En congé");
                } else {
                    SuivrePointage::create([
                        'iduser'        => $user->id,
                        'date_pointage' => $date->copy(),
                        'type'          => 'absence',
                        'description'   => 'Absence historique détectée (Système)',
                    ]);
                    $this->line("   ❌ {$date->format('Y-m-d')} - Absence");
                }

                $date->addDay();
            }
        }

        $this->info("✅ Détection historique terminée avec succès!");
        return 0;
    }
}