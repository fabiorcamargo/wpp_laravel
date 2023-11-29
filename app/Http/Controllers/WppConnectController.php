<?php

namespace App\Http\Controllers;

use App\Http\Requests\WppRules;
use App\Jobs\WppInstanceCreate;
use App\Jobs\WppInstanceMessageSend;
use App\Jobs\WppInstanceStartSession;
use App\Jobs\WppInstanceStatus;
use App\Models\WppConnect;
use GuzzleHttp\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WppConnectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datas = auth()->user()->getWpp()->get();

        return view('wpp.index', ['datas' => $datas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WppRules $request)
    {

        $request['session'] = (string) Str::orderedUuid();
        $request['status'] = 'CRIANDO';
        $wpp = auth()->user()->getWpp()->create($request->all());

        
        $request->session()->flash('flash.banner', 'Instância enviada para criação');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show($wppConnect)
    {
        
        return view('wpp.show', ['wpp' => WppConnect::find($wppConnect)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WppConnect $wppConnect)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WppConnect $wppConnect)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        WppConnect::find($id)->delete();

        return back()->banner('Instância excluída com sucesso');
    }



    public function QrCode($id)
    {
        $wpp = WppConnect::find($id);

        $url = 'https://api.meusestudosead.com.br/api/' . $wpp->session .  '/qrcode-session';
        try {
            $client = new Client();

            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $wpp->token,
                ],
            ]);

            // Verifique se a resposta é bem-sucedida (código 200)
            if ($response->getStatusCode() === 200) {
                $image = $response->getBody(); // Obtém o stream da resposta

                // Salvar o stream em um arquivo temporário
                Storage::put('qr.png', $image);
        
                // Caminho para o arquivo salvo
                $imagePath = Storage::path('qr.png');
        
                // Retornar a imagem como resposta
                return response()->file($imagePath);
            } else {
                // Lidar com erros de resposta, se necessário
                return response()->json(['error' => 'Erro ao obter o QR code'], $response->getStatusCode());
            }
        } catch (RequestException $e) {
            // Lidar com exceções, se ocorrerem
            return response()->json(['error' => 'Erro na requisição: ' . $e->getMessage()], 500);
        }
    }

    public function StartSession($id)
    {

        $wpp = WppConnect::find($id);

        $url = 'https://api.meusestudosead.com.br/api/' . $wpp->session .  '/start-session';

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $wpp->token,
            ])->post($url);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados
                
                $responseData = $response->json();
                $status = $responseData['status'];

                $wpp->update([
                    'status' => $status
                ]);

                return $status;
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

    public function StatusSession($id)
    {

        $wpp = WppConnect::find($id);

        $url = 'https://api.meusestudosead.com.br/api/' . $wpp->session .  '/status-session';

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $wpp->token,
            ])->get($url);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $responseData = $response->json();
                $status = $responseData['status'];

                $wpp->update([
                    'status' => $status
                ]);
                return $status;
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

    public function SendMessage($name, $phone, $msg)
    {
     
        $wpp = WppConnect::where('name', $name)->first();
        $phone = "55" . $phone;

        dispatch(new WppInstanceMessageSend($wpp->phone, $phone, $msg));
        
    }

    public function StopInstance($id)
    {

        $wpp = WppConnect::find($id);

        $url = 'https://api.meusestudosead.com.br/api/' . $wpp->session .  '/close-session';

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $wpp->token,
            ])->post($url);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $responseData = $response->json();
                $status = $responseData['status'];

                $wpp->update([
                    'status' => $status
                ]);
                return $status;
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
