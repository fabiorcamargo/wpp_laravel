<?php

namespace App\Listeners;

use App\Events\UploadConcluido;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UploadConcluidoListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UploadConcluido $event): void
    {
        Log::info('Job concluído com sucesso!');
    }
}
