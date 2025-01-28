<?php

namespace App\Jobs;

use App\Models\WppConnect;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WppInstanceCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $wpp;
    protected $body;

    /**
     * Create a new job instance.
     */
    public function __construct(WppConnect $wpp)
    {
        $this->url = env('URL_API') . '/sessions/add';
        $this->wpp = $wpp;

        $this->body = [
            "sessionId" => $this->wpp->session
        ];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'x-api-key' => env('WPP_KEY')
            ])->post($this->url, $this->body);

            // Obtenha o corpo da resposta como uma string
            $responseBody = $response->getBody()->getContents();

            // Você pode fazer o que quiser com $responseBody, como convertê-lo em um array JSON
            $data = json_decode($responseBody, true);

                //dd($data);

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                // A solicitação foi bem-sucedida
                // Faça algo com os dados
                // $this->wpp->update([
                //     'token' => $data['hash']['apikey'],
                //     'status' => $data['instance']['status']
                // ]);

                //dispatch(new WppInstanceStartSession($this->wpp));

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

                $this->wpp->update([
                    'status' => 'ERRO'
                ]);
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                $this->wpp->update([
                    'status' => 'ERRO'
                ]);
            }
        }
    }
}
