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
        $attendanceDate = Carbon::parse($log['timestamp']);
        
        // التحقق واش ديجا كاين
        $exists = SuivrePointage::where('iduser', $user->id)
                    ->where('date', $attendanceDate->toDateTimeString())
                    ->exists();

        if (!$exists) {
            SuivrePointage::create([
                'iduser' => $user->id,
                'date'   => $attendanceDate,
            ]);
            // سطر جديد باش يطبع ليك السمية في Terminal
            $this->info("✔ سجلنا بصمة جديدة لـ: " . $user->name . " بتاريخ " . $attendanceDate);
        } else {
            // سطر باش يقول ليك راه هاد البصمة كاين ديجا
            $this->line("ℹ البصمة ديال " . $user->name . " ديجا مسجلة.");
        }
    } else {
        // إذا كان شي واحد بصم في الماكينة وماعندوش كود في السيستم
        $this->warn("⚠ كود الماكينة (" . $log['id'] . ") ما كاينش في جدول الموظفين.");
    }
}

            $zk->disconnect();
            $this->info("✅ تمت العملية بنجاح.");
        } else {
            $this->error("❌ فشل الاتصال! تأكدي من الروتر والـ IP.");
        }
    }
}