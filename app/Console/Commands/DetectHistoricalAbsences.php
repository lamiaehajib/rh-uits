<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class DetectHistoricalAbsences extends Command
{
    protected $signature = 'absences:detect-historical {--from=} {--to=} {--user=}';
    protected $description = 'Détecter toutes les absences historiques (Exclut les clients, admins et IDs invalides)';

    public function handle()
    {
        // تحديد تاريخ البداية (إما من خيار --from أو أول بصمة في النظام)
        $premierPointage = SuivrePointage::min('date_pointage');
        
        $dateDebut = $this->option('from') 
            ? Carbon::parse($this->option('from'))
            : ($premierPointage ? Carbon::parse($premierPointage) : Carbon::now()->subMonths(3));
            
        // تحديد تاريخ النهاية (البارح هو الافتراضي)
        $dateFin = $this->option('to')
            ? Carbon::parse($this->option('to'))
            : Carbon::yesterday('Africa/Casablanca');

        $this->info("🔍 Détection historique du {$dateDebut->format('Y-m-d')} au {$dateFin->format('Y-m-d')}");
        
        $joursSemaine = [
            'Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi', 'Sunday' => 'Dimanche'
        ];

        // 1. جلب المستخدمين المستهدفين مع استثناء الأدوار والـ IDs غير الموجودة
        $usersQuery = User::where('is_active', true)
            ->whereNotNull('id') // التأكد أن المستخدم لديه ID في قاعدة البيانات
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['client', 'Sup_Admin', 'Custom_Admin']);
            });

        // تصفية بمستخدم معين إذا تم تحديد --user
        if ($userId = $this->option('user')) {
            $usersQuery->where('id', $userId);
        }

        $users = $usersQuery->get();
        $this->info("👥 {$users->count()} utilisateur(s) valides à traiter");

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
                
                // البحث عن حضور باستخدام ID المستخدم الصريح
                $hasPresence = SuivrePointage::where('iduser', $user->id)
                    ->whereDate('date_pointage', $date)
                    ->where('type', 'presence')
                    ->exists();

                $hasAbsence = SuivrePointage::where('iduser', $user->id)
                    ->whereDate('date_pointage', $date)
                    ->where('type', 'absence')
                    ->exists();

                // تسجيل الغياب فقط إذا لم تتوفر الشروط أعلاه
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
        $this->newLine(2);
        $this->info("✅ Traitement historique terminé!");
        $this->info("📊 Total des absences ajoutées: {$totalAbsences}");
        
        return 0;
    }
}