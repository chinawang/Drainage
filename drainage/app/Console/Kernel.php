<?php

namespace App\Console;

use App\Jobs\StationRecord;
use App\Jobs\RecordClean;
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
        // $schedule->command('inspire')
        //          ->hourly();

        // 导出运行记录
//        $schedule->job(new StationRecord)->everyMinute()->runInBackground();
        $schedule->job(new StationRecord)->dailyAt('20:55')->runInBackground();

        // 清除实时记录
//        $schedule->job(new RecordClean)->everyMinute()->runInBackground();
        $schedule->job(new RecordClean)->monthlyOn(1, '23:00')->runInBackground();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
