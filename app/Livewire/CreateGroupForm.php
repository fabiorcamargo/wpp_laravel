<?php

namespace App\Livewire;

use Livewire\Component;

class CreateGroupForm extends Component
{

    public $phones;
    public $phone;
    public $name;
    public $isVisible = true;


    public function render()
    {
        return view('livewire.create-group-form');
    }
    
}
