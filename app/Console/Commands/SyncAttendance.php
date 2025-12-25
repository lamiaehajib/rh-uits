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
    $user = User::where('code', $log['id'])->first();

    if ($user) {
        $fullTimestamp = \Carbon\Carbon::parse($log['timestamp']);
        $dateOnly = $fullTimestamp->toDateString();

        // كنقلبو على آخر بوانتاج ديال هاد الموظف لي باقي "مفتوح" (مافيهش heure_depart)
        $lastPointage = SuivrePointage::where('iduser', $user->id)
                                     ->whereDate('date_pointage', $dateOnly)
                                     ->whereNull('heure_depart')
                                     ->first();

        if (!$lastPointage) {
            // إلا مالقينا حتى بوانتاج مفتوح، كنفتحو واحد جديد (Arrivée)
            SuivrePointage::create([
                'iduser'         => $user->id,
                'date_pointage'  => $dateOnly,
                'heure_arrivee'  => $fullTimestamp,
                'description'    => 'Pointage via Machine F18',
                'localisation'   => 'Office (Titre Mellil)'
            ]);
            $this->info("✔ Arrivée: " . $user->name . " à " . $fullTimestamp);
        } else {
            // إلا لقينا واحد مفتوح، ودازت على الأقل 5 دقائق (باش نفاداو التكرار الخطأ)
            if ($fullTimestamp->diffInMinutes($lastPointage->heure_arrivee) > 5) {
                $lastPointage->update([
                    'heure_depart' => $fullTimestamp
                ]);
                $this->line("⏳ Départ: " . $user->name . " à " . $fullTimestamp);
            }
        }
    }
}
            $zk->disconnect();
            $this->info("✅ تمت العملية بنجاح.");
        } else {
            $this->error("❌ فشل الاتصال! تأكدي من الروتر والـ IP.");
        }
    }
}