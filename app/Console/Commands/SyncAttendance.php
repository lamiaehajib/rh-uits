<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Rats\Zkteco\Lib\ZKTeco;
use App\Models\User;
use App\Models\SuivrePointage;
use Carbon\Carbon;

class SyncAttendance extends Command
{
    // زدت ليك هنا --date باش تقدر تختار نهار محدد إلا بغيتي
    protected $signature = 'attendance:sync {--date= : المزامنة لتاريخ محدد YYYY-MM-DD}';
    protected $description = 'جلب البصمات من الماكينة وحفظها في قاعدة البيانات';

    public function handle()
    {
        $ip = config('services.zkteco.ip', env('ZK_DEVICE_IP'));
        $port = config('services.zkteco.port', env('ZK_DEVICE_PORT'));
        $targetDate = $this->option('date');

        $zk = new ZKTeco($ip, $port);
        $this->info("جاري الاتصال بالماكينة...");

        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            $count = 0;

            foreach ($attendance as $log) {
                $fullTimestamp = Carbon::parse($log['timestamp']);
                $dateOnly = $fullTimestamp->toDateString();

                // إلا حددتي تاريخ، كيدوز غير البصمات ديال داك النهار
                if ($targetDate && $dateOnly !== $targetDate) continue;

                $user = User::where('code', $log['id'])->first();

                if ($user) {
                    $alreadyExists = SuivrePointage::where('iduser', $user->id)
                                        ->where(function($query) use ($fullTimestamp) {
                                            $query->where('heure_arrivee', $fullTimestamp)
                                                  ->orWhere('heure_depart', $fullTimestamp);
                                        })->exists();

                    if ($alreadyExists) continue;

                    $lastPointage = SuivrePointage::where('iduser', $user->id)
                                                 ->whereDate('date_pointage', $dateOnly)
                                                 ->whereNull('heure_depart')
                                                 ->first();

                    if (!$lastPointage) {
                        SuivrePointage::create([
                            'iduser'         => $user->id,
                            'date_pointage'  => $dateOnly,
                            'heure_arrivee'  => $fullTimestamp,
                            'type'           => 'presence', // هادي مهمة باش يطلع حاضر
                            'description'    => 'Pointage via Machine F18',
                            'localisation'   => 'Office (Titre Mellil)'
                        ]);
                        $count++;
                    } else {
                        if ($fullTimestamp->diffInMinutes($lastPointage->heure_arrivee) > 5) {
                            $lastPointage->update([
                                'heure_depart' => $fullTimestamp,
                                'type'         => 'presence'
                            ]);
                            $count++;
                        }
                    }
                }
            }
            $zk->disconnect();
            $this->info("✅ تمت العملية بنجاح. تم تسجيل $count بصمة جديدة.");
        } else {
            $this->error("❌ فشل الاتصال! تأكدي من الروتر والـ IP.");
        }
    }
}