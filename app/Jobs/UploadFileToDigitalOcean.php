<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadFileToDigitalOcean
{
    use Dispatchable;

    protected $name;
    protected $tempFilePath;
    

    public function __construct($name, $tempFilePath)
    {
        $this->tempFilePath = $tempFilePath;
        $this->name = $name;
    }

    // Restante do código da tarefa de fila

    public function handle()
    {
        // Acesse o caminho do arquivo temporário
        $tempFilePath = $this->tempFilePath;
        $name = $this->name;

        // Faça o upload do arquivo para o armazenamento em nuvem ou realize qualquer outra operação necessária
        // ...
        Storage::disk('do_spaces')->put($name, file_get_contents($tempFilePath));

        // Limpe o arquivo temporário quando o processamento estiver concluído
        Storage::delete($tempFilePath);
    }
}