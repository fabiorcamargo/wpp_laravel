<?php

namespace App\Console;

use App\Jobs\WppScheduleJob;
use App\Models\WppSchedule;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $now = now()->format('H:i:s');
            $nowm = now()->addMinute()->format('H:i:s');
            $day = Carbon::parse(now());

            $firstJob = WppSchedule::where('time', '>=', $now)
                ->where('time', '<', $nowm)
                ->where('repeat', '>=',  1)
                ->where('date', '<=',  $day)
                ->first();

            if ($firstJob) {
                dispatch(new WppScheduleJob($firstJob, $now, $nowm, $day));
            }
        })->everyMinute()->name('Call-Schedule-Job');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
