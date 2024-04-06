<?php

namespace App\Livewire;

use Livewire\Component;

class WppIndex extends Component
{
    public $datas;
    public function render()
    {
        $this->datas = auth()->user()->getWpp()->get();

        return view('livewire.wpp-index');
    }
}
