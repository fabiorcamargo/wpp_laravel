<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use App\Models\WppImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SendMsgForm extends Component
{

    use WithFileUploads;

    public $phone;
    public $session;
    public $msg;
    public $isVisible = true;
    public $wpp;

    public $img;
    public $imgUrl;
    public $photos;
    public $selectedPhoto;
    public $type = 'text';
    public $header;

    public $textInputs = [];
    public $imageInputs = [];

    protected $rules = [
        'phone' => ['required', 'regex:/^\d{2}\s*9\d{8}$/'],
        'msg' => ['required'],
    ];

    protected $messages = [
        'phone.regex' => 'O Formato do telefone tem que seguir o exemplo 449987654321.',
        'phone.required' => 'O número de telefone não pode estar vazio.',
        'msg.required' => 'A mensagem não pode ser vazia',
    ];


    public function gallery()
    {
        $this->photos = $this->wpp->getImage()->get();
        //dd('s');
    }

    public function selectPhoto($photo)
    {

        $this->selectedPhoto = $photo;
    }

    public function clearSelection(){
        $this->selectedPhoto = "";
    }

    public function saveImg()
    {

        $this->validate(['img' => 'required|mimes:jpg,png']);

        $path = $this->img->storeAs('uploads', uuid_create() . '.' . $this->img->getClientOriginalExtension());

        $imageUrl = asset("storage/{$path}");

        $this->wpp->getImage()->create([
            'url' => $imageUrl
        ]);

        $this->imgUrl = $imageUrl;

        $this->gallery();
    }

    public function deleteImg($id)
    {
        $img = WppImage::find($id);
        if ($img) {
            $filePath = parse_url($img->url, PHP_URL_PATH); // Pega o caminho relativo
            $filePath = ltrim($filePath, '/'); // Remove a barra inicial se existir
    
            if (file_exists($filePath)) {
                unlink($filePath); // Remove o arquivo
            }
            $img->delete(); // Remove do banco
            return response()->json(['message' => 'Imagem deletada com sucesso']);
        }
    

     
    }

    

    public function submitForm(Request $request)
    {
        $this->validate();

        $this->msg = ['img' => $this->selectedPhoto, 'text' => $this->msg];
        
        //dd($this->msg);

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
        $this->gallery();
        return view('livewire.send-msg-form');
    }
}
