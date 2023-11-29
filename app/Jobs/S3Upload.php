<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class S3Upload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    public $timeout = 10200;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function handle()
    {
        $name = $this->name;
        
            //dd($Path);
            // Use o Artisan para executar o comando personalizado
           $path = "storage/app/public/temp/$name";

            $s3cmdCommand = "s3cmd put $path s3://profissionaliza-space";
            exec($s3cmdCommand, $output, $returnCode);
            
    
            // Após a conclusão do comando, você pode executar qualquer
            // código adicional que desejar aqui.
    
            // Por exemplo, você pode registrar que o arquivo foi enviado
            // ou realizar outras ações relacionadas.
        
    }
}
