<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('objectifs:update-progress')->dailyAt('01:00'); // Run daily at 1 AM
        
        // ** هذا هو أمر الباك اب الأسبوعي الجديد **
        $schedule->command('backup:run --only-db')->weekly()->at('02:00');

        $schedule->command('attendance:sync')->everyFifteenMinutes();


      $schedule->command('absences:daily')
             ->everyFiveMinutes()
             ->timezone('Africa/Casablanca')
             ->appendOutputTo(storage_path('logs/absences.log'));


              $schedule->command('retards:traiter-deductions')
             ->monthlyOn(1, '00:00')
             ->timezone('Africa/Casablanca');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}