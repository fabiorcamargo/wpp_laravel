<?php

namespace App\Livewire;

use Livewire\Component;

class Qrcode extends Component
{
    public $qrCodeUrl;
    public $id;
    public $showQr = true; // Variável para controlar a exibição do componente

    public function mount($id)
    {
        $this->id = $id;
        $this->qrCodeUrl = route('qrcode', ['id' => $this->id]); // Substitua '1' pelo ID correto
    }

    public function render()
    {
        return view('livewire.qrcode');
    }

    public function regenerate()
    {
        $this->qrCodeUrl = route('qrcode', ['id' => $this->id]); // Substitua '1' pelo ID correto
        return view('livewire.qrcode');
    }
}
