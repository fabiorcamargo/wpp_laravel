<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class RetryFailedJobsWithDelay extends Command
{
    protected $signature = 'queue:retry-with-delay {delay=10}'; // Delay padrão de 10s
    protected $description = 'Reprocessa jobs com falha, aplicando um intervalo entre cada execução';

    public function handle()
    {
        $failedJobs = DB::table('failed_jobs')->pluck('id');

        foreach ($failedJobs as $jobId) {
            $this->info("Reprocessando job ID: $jobId");
            Artisan::call("queue:retry $jobId");
            sleep($this->argument('delay')); // Espera o tempo definido antes do próximo job
        }

        $this->info("Todos os jobs com falha foram reprocessados!");
    }
}
