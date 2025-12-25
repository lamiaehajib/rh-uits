<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class SyncAttendance extends Command
{
    // هاد السمية هي باش غنعيطو للكوموند في التي رمينال
    protected $signature = 'attendance:sync';
    protected $description = 'جلب البصمات من الماكينة وحفظها في قاعدة البيانات';

    public function handle()
    {
        // 1. نجيبو المعلومات من ملف .env
        $ip = config('services.zkteco.ip', env('ZK_DEVICE_IP'));
        $port = config('services.zkteco.port', env('ZK_DEVICE_PORT'));

        $zk = new ZKTeco($ip, $port);

        $this->info("جاري الاتصال بالماكينة...");

        if ($zk->connect()) {
            // 2. نجيبو كاع البصمات اللي في الماكينة
            $attendance = $zk->getAttendance();

          foreach ($attendance as $log) {
    // 1. البحث عن الموظف بالكود
    $user = User::where('code', $log['id'])->first();

    if ($user) {
        $fullTimestamp = \Carbon\Carbon::parse($log['timestamp']);
        $dateOnly = $fullTimestamp->toDateString(); // YYYY-MM-DD

        // 2. البحث عن سجل لهذا الموظف في هذا اليوم
        $pointage = SuivrePointage::where('iduser', $user->id)
                                  ->whereDate('date_pointage', $dateOnly)
                                  ->first();

        if (!$pointage) {
            // أول مرة يبصم اليوم -> نعتبرها وقت وصول
            SuivrePointage::create([
                'iduser'         => $user->id,
                'date_pointage'  => $dateOnly,
                'heure_arrivee'  => $fullTimestamp,
                'description'    => 'Pointage via Machine F18',
                'localisation'   => 'Office (Titre Mellil)'
            ]);
            $this->info("✔ تسجيل وصول جديد: " . $user->name);
        } else {
            // بصم مرة أخرى في نفس اليوم -> نحدث وقت المغادرة
            // (بشرط أن يكون الوقت الجديد أحدث من وقت الوصول)
            if ($fullTimestamp->gt($pointage->heure_arrivee)) {
                $pointage->update([
                    'heure_depart' => $fullTimestamp
                ]);
                $this->line("⏳ تحديث مغادرة: " . $user->name);
            }
        }
    } else {
        $this->warn("⚠ كود (" . $log['id'] . ") غير مرتبط بموظف.");
    }
}

            $zk->disconnect();
            $this->info("✅ تمت العملية بنجاح.");
        } else {
            $this->error("❌ فشل الاتصال! تأكدي من الروتر والـ IP.");
        }
    }
}