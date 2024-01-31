<?php

namespace App\Console;

use App\Models\Core\Job;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\TornadocmsTornadocore\Console\GestionCaducidades;

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
        $jobs = Job::where('active','1')->get();
        foreach($jobs as $j){

            if(strlen($j->param1)  == 0 && strlen($j->param2) == 0 && strlen($j->param3) == 0){
                $time = $j->time;
                $schedule->command($j->command)->$time();
            }
            else if(strlen($j->param1) > 0 && strlen($j->param2) == 0 && strlen($j->param3) == 0){
                $time = $j->time;
                $schedule->command($j->command)->$time($j->param1);
            }
            else if(strlen($j->param1) > 0 && strlen($j->param2) > 0 && strlen($j->param3) == 0){
                $time = $j->time;
                $schedule->command($j->command)->$time($j->param1,$j->param2);
            }
            else if(strlen($j->param1) > 0 && strlen($j->param2) > 0 && strlen($j->param3) > 0){
                $time = $j->time;
                $schedule->command($j->command)->$time($j->param1,$j->param2,$j->param3);
            }
        }
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
