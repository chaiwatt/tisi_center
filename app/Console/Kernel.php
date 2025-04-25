<?php

namespace App\Console;

use Appzcoder\CrudGenerator\Commands\CrudCommand;
use Appzcoder\CrudGenerator\Commands\CrudControllerCommand;
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
        CrudCommand::class,
        CrudControllerCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 0 1 * * * php /var/www/html/tisi_center/artisan schedule:run >> /dev/null 2>&1
        $schedule->command('run:all-schedules')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
