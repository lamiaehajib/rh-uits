<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function download()
    {
        // 1. Run the backup command to create a new backup file
        //    We use try-catch to avoid stopping the whole process if backup fails
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
        } catch (\Exception $e) {
            // Log the error but continue to the download step
            \Log::error("Backup failed during download attempt: " . $e->getMessage());
        }

        // 2. Find the latest backup file in the local storage
        $backupDisk = Storage::disk('local');
        $files = $backupDisk->allFiles('tachehiv_backups'); // Use the correct folder name

        // Find the latest file in the array of files
        $latestBackup = end($files);

        if ($latestBackup) {
            // 3. Send the file to the browser for download
            return $backupDisk->download($latestBackup, basename($latestBackup));
        }

        // 4. If no backup file is found, return an error message
        return back()->with('error', 'No backup file found to download.');
    }
}