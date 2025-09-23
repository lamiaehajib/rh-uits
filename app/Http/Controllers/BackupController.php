<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function downloadBackup()
    {
        // تشغيل أمر النسخ الاحتياطي
        Artisan::call('backup:run', ['--only-db' => true]);

        // إيجاد أحدث ملف Backup
        $backupDisk = Storage::disk('local');
        $files = $backupDisk->allFiles('my-backup');
        $latestBackup = end($files);

        if ($latestBackup) {
            // إعادة اسم الملف إلى اسم واضح
            $fileName = 'backup-' . now()->format('Y-m-d') . '.zip';

            // إرسال الملف للمتصفح لتنزيله
            return $backupDisk->download($latestBackup, $fileName);
        }

        return back()->with('error', 'No backup file found.');
    }
}