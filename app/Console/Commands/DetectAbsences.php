<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectAbsences extends Command
{
    // الأمر اللي كيتنفذ في الـ VPS
    protected $signature = 'absences:daily {--date=}';
    protected $description = 'Détecter les absences (Exclut Clients, Admins et IDs non valides)';

    public function handle()
    {
        // تحديد التاريخ (البارح هو الافتراضي)
        $date = $this->option('date') 
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday('Africa/Casablanca');

        $this->info("🔍 Analyse des absences pour le : {$date->format('Y-m-d')}");

        $joursSemaine = [
            'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
        ];
        
        $jourActuel = $joursSemaine[$date->englishDayOfWeek];

        // 1. جلب المستخدمين مع الفلترة الصارمة
        $users = User::where('is_active', true)
            // استثناء الأدوار التي لا تبصم
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            })
            // التأكد أن المستخدم لديه ID صالح (موجود في الماكينة وقاعدة البيانات)
            ->whereNotNull('id') 
            ->get();

        $absencesDetectees = 0;

        foreach ($users as $user) {
            // 2. التحقق من يوم الراحة (Repos)
            $joursRepos = $user->repos ?? [];
            if (in_array($jourActuel, $joursRepos)) {
                $this->line("⏭️  {$user->name} (ID: {$user->id}) - Jour de repos ({$jourActuel})");
                continue;
            }

            // 3. التحقق من وجود بصمة (Presence) في جدول Pointage
            // هنا كنعتمدو على ID المستخدم لربط البيانات
            $pointageExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'presence')
                ->exists();

            if ($pointageExiste) {
                $this->line("✅ {$user->name} (ID: {$user->id}) - Présent");
                continue;
            }

            // 4. التحقق إذا كان الغياب مسجل مسبقاً لتفادي التكرار
            $absenceExiste = SuivrePointage::where('iduser', $user->id)
                ->whereDate('date_pointage', $date)
                ->where('type', 'absence')
                ->exists();

            if (!$absenceExiste) {
                // تسجيل الغياب في قاعدة البيانات
                SuivrePointage::create([
                    'iduser'        => $user->id,
                    'date_pointage' => $date,
                    'type'          => 'absence',
                    'description'   => 'Absence détectée automatiquement (Système)',
                    'localisation'  => 'N/A',
                    'heure_arrivee' => null,
                    'heure_depart'  => null,
                ]);

                $this->error("❌ {$user->name} (ID: {$user->id}) - ABSENCE ENREGISTRÉE");
                $absencesDetectees++;
            } else {
                $this->line("⚠️  {$user->name} - Absence déjà marquée");
            }
        }

        $this->info("✅ Opération terminée. {$absencesDetectees} nouvelle(s) absence(s).");
        return 0;
    }
}