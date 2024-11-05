<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    #TODO
    // get it scheduled on server in cron tab
    ///    * * * * * php /path-to-your-lumen-app/artisan schedule:run >> /dev/null 2>&1

    protected $commands = [
        // Register custom commands here if any
       //  \App\Console\Commands\ProcessCallback::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('process-callback')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Load commands from the Commands directory if needed
        $this->load(__DIR__ . '/Commands');
    }
}
