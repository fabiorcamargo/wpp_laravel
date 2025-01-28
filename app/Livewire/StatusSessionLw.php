<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use App\Models\WppConnect;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class StatusSessionLw extends Component
{
    public $status = 'Criar Sessão';
    public $qr;
    public $id = '';
    public $wpp;
    public $isVisible = true;


    public function sendRequest()
    {
        // Lógica para enviar a requisição (simulada)
        // Substitua isso com a lógica real para enviar a requisição
        //$this->status = "Enviando requisição com ID: " . $this->id;
        $wpp = new WppConnectController;
        $this->qr = $wpp->StartSession($this->id);
        //dd($this->qr);

    }

    public function stopSend() {
        //Artisan::call('horizon:clear', ['--queue' => 'zapfabio']);

        shell_exec('php /home/fabio/laravel/zap/artisan horizon:clear');

        session()->flash('message', 'Envio parado com sucesso!');

    }

    public function dismiss()
    {
        $this->isVisible = false;

    }

    public function render() {
        //dd('s');
        $wpp = WppConnect::find($this->id);
        
        $this->status = $wpp->status;

        //dd($this->status);
        $this->qr = $wpp->QrCode->qr_code ?? '';
        //dd($this->qr);
        
        if($this->status == "connecting"){
            $this->sendRequest();
        }
        $this->wpp = WppConnect::find($this->id);
        return view('livewire.status-session-lw', );
    }

    public function status() {
        $wpp = new WppConnectController;
        $this->status = $wpp->StatusSession($this->id);

        
        
    }

    public function StopInstance() {
        $wpp = new WppConnectController;
        $this->status = $wpp->StopInstance($this->id);
    }
}
