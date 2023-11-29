<?php

namespace App\Livewire;

use App\Models\WppConnect;
use Livewire\Component;

class MessagesTable extends Component
{
    public $wpp;
    public function render()
    {

        $wpp = WppConnect::find($this->wpp);
        $mensagens = $wpp->Messages()->orderBy('created_at', 'desc')->paginate(10);


        return view('livewire.messages-table', ['mensagens' => $mensagens]);
    }
}
