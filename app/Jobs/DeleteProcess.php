<?php

namespace App\Jobs;

use App\Models\Files;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $name;
    

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         // Acesse o caminho do arquivo temporário

         // Faça o upload do arquivo para o armazenamento em nuvem ou realize qualquer outra operação necessária
         // ...
 
         $files = Files::find($this->id);
         $files->delete();
         
         // Limpe o arquivo temporário quando o processamento estiver concluído

         //event(new UploadConcluido($this));
    }
}
