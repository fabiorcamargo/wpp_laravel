<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class StopJobsByTag extends Command
{
    protected $signature = 'wppbatch:stopjobs {batchId}';
    protected $description = 'Interrompe os jobs de um lote específico usando a tag';

    public function handle()
    {
        $batchId = $this->argument('batchId');
        
        // Usando o Horizon para encontrar jobs com a tag do lote
        $jobs = Queue::getJobsByTag('App\Models\WppBatch:' . $batchId);

        foreach ($jobs as $job) {
            // Cancelando os jobs encontrados
            $job->delete();
        }

        $this->info('Jobs do lote ' . $batchId . ' interrompidos!');
    }
}
