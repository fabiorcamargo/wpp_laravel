<?php

namespace App\Jobs;

use App\Events\UploadConcluido;
use App\Models\Files;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $name;
    protected $tempFilePath;
    protected $fileid;
    public $timeout = 10200;

    public function __construct($name, $tempFilePath, $fileid)
    {
        $this->tempFilePath = $tempFilePath;
        $this->name = $name;
        $this->fileid = $fileid;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         // Acesse o caminho do arquivo temporário
         $tempFilePath = $this->tempFilePath;
         $name = $this->name;
 
         // Faça o upload do arquivo para o armazenamento em nuvem ou realize qualquer outra operação necessária
         // ...
         Storage::disk('do_spaces')->put($name, file_get_contents($tempFilePath));
 
         $files = Files::find($this->fileid->id);
         $files->update(['status' => 'Concluído']);
         // Limpe o arquivo temporário quando o processamento estiver concluído
         

         Storage::disk('temp_uploads')->delete($name);
         
         event(new UploadConcluido($this));
    }
}
