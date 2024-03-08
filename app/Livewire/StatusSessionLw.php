<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use App\Models\WppConnect;
use Livewire\Component;

class StatusSessionLw extends Component
{
    public $status = 'Criar Sessão';
    public $qr;
    public $id = '';
    public $wpp;

    public function sendRequest()
    {
        // Lógica para enviar a requisição (simulada)
        // Substitua isso com a lógica real para enviar a requisição
        //$this->status = "Enviando requisição com ID: " . $this->id;
        $wpp = new WppConnectController;
        $this->qr = $wpp->StartSession($this->id);
        //dd($this->qr);

    }

    public function render() {
        $wpp = new WppConnectController;
        $this->status = $wpp->StatusSession($this->id);
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
