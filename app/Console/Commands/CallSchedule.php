<?php

namespace App\Console\Commands;

use App\Http\Controllers\WppConnectController;
use App\Jobs\WppScheduleJob;
use App\Models\WppConnect;
use Illuminate\Console\Command;

class CallSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:call-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chama o trabalho agendado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $job = new WppScheduleJob;
        dispatch($job);
    
    }
}
