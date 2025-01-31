<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Laravel\Horizon\Contracts\JobRepository;

class RetryFailedJobsWithDelay extends Command
{
    protected $signature = 'horizon:retry-with-delay {delay=10}';
    protected $description = 'Reprocessa jobs com falha no Horizon com um intervalo entre cada tentativa';

    public function handle(JobRepository $jobs)
{
    $failedJobs = $jobs->getFailed(); // Obtém os jobs com falha

    foreach ($failedJobs as $job) {
        if (!DB::table('failed_jobs')->where('id', $job->id)->exists()) {
            continue; // Pula jobs que já foram processados com sucesso
        }

        $jobId = $job->id;
        $this->info("Reprocessando job ID: $jobId");
        Artisan::call("queue:retry $jobId");
        sleep($this->argument('delay'));
    }

    $this->info("Todos os jobs pendentes foram reprocessados!");
}
}

