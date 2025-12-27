<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function download()
{
    try {
        // 1. تشغيل الكوموند
        Artisan::call('backup:run', ['--only-db' => true]);
        
        $backupDisk = Storage::disk('local');
        
        // 2. جلب جميع الملفات مع التواريخ ديالها
        $files = $backupDisk->allFiles(); // كيجيب كاع الملفات حتى اللي وسط المجلدات

        if (empty($files)) {
            return back()->with('error', 'لا يوجد أي ملف باك أب.');
        }

        // 3. ترتيب الملفات حسب تاريخ التعديل (الأحدث أولاً)
        usort($files, function ($a, $b) use ($backupDisk) {
            return $backupDisk->lastModified($b) <=> $backupDisk->lastModified($a);
        });

        // 4. تحميل أحدث ملف
        $latestBackup = $files[0];
        
        return $backupDisk->download($latestBackup, basename($latestBackup));

    } catch (\Exception $e) {
        \Log::error("Backup failed: " . $e->getMessage());
        return back()->with('error', 'فشلت عملية الباك أب: ' . $e->getMessage());
    }
}
}