<?php

namespace App\Livewire;

use App\Http\Controllers\WppConnectController;
use App\Models\WppConnect;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class WppIndex extends Component
{
    public $datas;
    public $response;
    public function render()
    {
        $this->datas = auth()->user()->getWpp()->get();
        $wpp = new WppConnectController;
        foreach ($this->datas as $data){
            $status = $wpp->StatusSession($data->id);
            //dd($status);
            $response[] = ['id' => $data->id, 'status' => $status];
         }

        return view('livewire.wpp-index');
    }

    public function StartSession($id)
    {

        $wpp = WppConnect::find($id);

        $url = env('URL_API') . '/instance/connect/' . $wpp->session . '?number=' . $wpp->phone;

        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'apikey' => $wpp->token
            ])->get($url);

            //dd(json_decode($response->body()));

            if ($response->getStatusCode() === 200) {
                $image = json_decode($response->body()); // Obtém o stream da resposta

                
                //$image = $image->base64;

                //dd($image);

                // Salvar o stream em um arquivo temporário
                // Storage::put('qr.png', $image['base64']);

                // Caminho para o arquivo salvo
                // $imagePath = Storage::path('qr.png');

                // Retornar a imagem como resposta
                return back();
            } else {
                // Lidar com erros de resposta, se necessário
                return response()->json(['error' => 'Erro ao obter o QR code'], $response->getStatusCode());
            }

           
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

    public function StopInstance($id)
    {

        $wpp = WppConnect::find($id);

        $url = env('URL_API') . '/instance/logout/' . $wpp->session;

        //dd($url);
        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'apikey' => env('WPP_KEY')
            ])->delete($url);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $responseData = $response->json();
                //dd($responseData);
                //$status = $responseData['instance']['state'];

                $wpp->update([
                     'status' => 'close'
                 ]);
                return back();
            } else {
                // Lidar com erros de resposta HTTP
                echo 'Erro na solicitação: ' . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

}
