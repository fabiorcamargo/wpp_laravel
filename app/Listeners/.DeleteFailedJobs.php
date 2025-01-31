<?php

use Laravel\Horizon\Events\JobFailed;
use Laravel\Horizon\Horizon;

class DeleteFailedJobs
{
    public function handle(JobFailed $event)
    {
        // Obtém o ID do Job falhado
        $jobId = $event->job->getJobId();

        if ($jobId) {
            // Remove o job falhado da lista do Horizon
            Horizon::forget($jobId);
        }
    }
}