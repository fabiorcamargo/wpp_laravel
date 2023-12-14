<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SendMsgForm extends Component
{

    public $phone;
    public $session;
    public $msg;
    public $isVisible = true;

    protected $rules = [
        'phone' => ['required', 'regex:/^\d{2}\s*9\d{8}$/'],
        'msg' => ['required'],
    ];

    protected $messages = [
        'phone.regex' => 'O Formato do telefone tem que seguir o exemplo 449987654321.',
        'phone.required' => 'O número de telefone não pode estar vazio.',
        'msg.required' => 'A mensagem não pode ser vazia',
    ];

    

    public function submitForm(Request $request)
    {
        $this->validate();

        $wpp = new WppConnectController;
        $wpp->SendMessage($this->session, $this->phone, $this->msg, false);


        $this->phone = '';
        $this->msg = '';
        
        session()->flash('message', 'Mensagem enviada para fila com sucesso!');

        //$request->session()->flash('flash.banner', 'Mensagem enviada para fila com sucesso!');
        
    }

    public function dismiss()
    {
        $this->isVisible = false;

    }


    public function render()
    {
        return view('livewire.send-msg-form');
    }
}
