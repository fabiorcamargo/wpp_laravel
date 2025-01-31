<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Laravel\Horizon\Contracts\JobRepository;

class RetryFailedJobsWithDelay extends Command
{
    protected $signature = 'horizon:retry-with-delay {delay=10}';
    protected $description = 'Reprocessa jobs com falha no Horizon com um intervalo entre cada tentativa';

    public function handle(JobRepository $jobs)
    {
        $failedJobs = $jobs->getFailed(); // Pega os jobs com falha no Horizon

        foreach ($failedJobs as $job) {
            $jobId = $job->id;
            $this->info("Reprocessando job ID: $jobId");
            Artisan::call("queue:retry $jobId");
            sleep($this->argument('delay')); // Aguarda X segundos antes do próximo
        }

        $this->info("Todos os jobs com falha foram reprocessados!");
    }
}

