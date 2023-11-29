<?php

namespace App\Jobs;

use App\Models\WppConnect;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WppInstanceMessageSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $phone;
    protected $number;
    protected $message;
    protected $wpp;


        public function __construct($phone, $number, $message)
    {
        $this->phone = $phone;
        $this->number = $number;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $wpp = WppConnect::where('phone', $this->phone)->first();

        $body = [
            "phone"=> $this->number,
            "message"=> $this->message,
            "isGroup"=> false
        ];

        $url = 'https://api.meusestudosead.com.br/api/' . $wpp->session .  '/send-message';

        try {
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $wpp->token,
            ])->post($url, $body);


           

            // Verifique o status da resposta
            if ($response->getStatusCode() === 201) {

            $data = $response->json()['response'][0];

            $data['wppid'] = $data['id'];
            $data['phone'] = $this->number;

                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $wpp->Messages()->create($data);
                
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
