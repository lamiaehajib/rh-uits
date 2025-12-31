<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectAbsences extends Command
{
    protected $signature = 'absences:daily {--date=}';
    protected $description = 'Détecter les absences quotidiennes (Exclut les clients et les admins)';

    public function handle()
    {
        // تحديد التاريخ: إما المعطى في الخيار أو تاريخ البارح
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

        // جلب المستخدمين النشطين مع استثناء الأدوار المحددة
        $users = User::where('is_active', true)
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            })
            ->get();

        $absencesDetectees = 0;

        foreach ($users as $user) {
            // 1. التحقق من يوم الراحة
            $joursRepos = $user->repos ?? [];
            if (in_array($jourActuel, $joursRepos)) {
                $this->line("⏭️  {$user->name} - Jour de repos ({$jourActuel})");
                continue;
            }

            // 2. التحقق من وجود بصمة حضور
            $pointageExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'presence')
                ->exists();

            if ($pointageExiste) {
                $this->line("✅ {$user->name} - Présent");
                continue;
            }

            // 3. التحقق من عدم تسجيل الغياب مسبقاً
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