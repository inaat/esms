<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
       // $schedule->command('queue:work --daemon')->everyMinute()->withoutOverlapping();
        $schedule->command('optimize:clear')->daily();
        $schedule->command('config:cache')->daily();
        $schedule->command('cache:clear')->daily();
        $schedule->command('auth:clear-resets')->weekly();
        $schedule->command('queue:work')->withoutOverlapping()->runInBackground();
        $schedule->command(' queue:retry all')->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command('queue:flush')->weekdays();
        $schedule->command('backup:clean')->dailyAt('13:00')->timezone('Asia/Karachi');	       
        $schedule->command('backup:run')->dailyAt('13:00')->timezone('Asia/Karachi');	

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
