<?php

namespace App\Console;

use App\Models\asig_toalla;
use App\Models\toalla;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use DateTime;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\GeneratorCommand::class,Commands\testtoalla::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->command('statoalla')->timezone('America/Caracas')->everyMinute();

       $schedule->command('queue:retry all')->everyMinute()->when(function () {
        $job = DB::table('failed_jobs')->select('*')->get();
        if(count($job) > 10){
            return true;
        }
    })->withoutOverlapping();
    $schedule->command('queue:work --stop-when-empty')->everyMinute()->when(function () {
            $job = DB::table('jobs')->select('*')->get();
            if(count($job) > 0){
                return true;
            }
        })->withoutOverlapping();
    $schedule->command('queue:flush')->everyMinute()->when(function () {
        $job = DB::table('failed_jobs')->select('*')->get();
        if(count($job) > 30){
            return true;
        }
    })->withoutOverlapping();

    }
}
