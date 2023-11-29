<?php

namespace App\Http\Controllers;

use App\Jobs\WppInstanceCreate;
use App\Models\WppConnect;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class WppRequest extends Controller
{
    private $wpp;

    public function __construct(WppConnect $wpp) {
        $this->wpp = $wpp;
    }

    public function Create() {

        $request['session'] = (string) Str::orderedUuid();
        $request['status'] = 'criando';

        dispatch(new WppInstanceCreate($this->wpp));

        //$request->session()->flash('flash.banner', 'Instância enviada para criação');

        return back();
        
    }


    public function StartSession()
    {

        $url = 'https://api.meusestudosead.com.br/api/' . $this->wpp->session .  '/start-session';

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->wpp->token,
            ])->post($url);

            $data = $response->json()['qrcode'];


            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados
                $this->wpp->update([
                    'status' => $data['status']
                ]);
                return back();
            } else {
                // Lidar com erros de resposta HTTP
                return 'Erro na solicitação: ' . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                return "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                return "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }


    public function QrCode()
    {

        $url = 'https://api.meusestudosead.com.br/api/' . $this->wpp->session .  '/qrcode-session';
        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->wpp->token,
            ])->post($url);

            // Verifique se a resposta é bem-sucedida (código 200)
            if ($response->getStatusCode() === 200) {
                $image = $response->getBody(); // Obtém o stream da resposta

                // Salvar o stream em um arquivo temporário
                $tempImagePath = tempnam(sys_get_temp_dir(), 'qr_');
                file_put_contents($tempImagePath, $image);

                // Retornar a imagem como resposta
                return response()->file($tempImagePath);

                // Ou, se preferir, você pode passar a imagem para a view do Laravel
                // return view('nome_da_view', ['qrCodeImagePath' => $tempImagePath]);
            } else {
                // Lidar com erros de resposta, se necessário
                return response()->json(['error' => 'Erro ao obter o QR code'], $response->getStatusCode());
            }
        } catch (RequestException $e) {
            // Lidar com exceções, se ocorrerem
            return response()->json(['error' => 'Erro na requisição: ' . $e->getMessage()], 500);
        }
    }

    public function delete()
    {

        $url = 'https://api.meusestudosead.com.br/api/' . $this->wpp->session .  '/clear-session-data';

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->wpp->token,
            ])->post($url);

            $data = $response->json();


            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados
                $this->wpp->update([
                    'status' => $data['status']
                ]);
                return back();
            } else {
                // Lidar com erros de resposta HTTP
                return 'Erro na solicitação: ' . $response->getStatusCode();
            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {
                // Se houver uma resposta HTTP no erro, você pode acessá-la
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                // Faça o que quiser com a resposta de erro
                return "Erro na solicitação: Status $statusCode, Response: $errorBody";

                /* $this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                return "Erro na solicitação: " . $e->getMessage();

                /*$this->wpp->update([
                    'status' => 'Erro'
                ]);*/
            }
        }
    }

}
