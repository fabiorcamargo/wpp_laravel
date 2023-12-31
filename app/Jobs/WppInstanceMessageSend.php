<?php

namespace App\Jobs;

use App\Models\WppConnect;
use App\Models\WppMessage;
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

    protected $message;
    protected $batch;


        public function __construct($mensagem)
    {
        $this->message = $mensagem;
        $mensagem->batch !== null ? $this->batch = $mensagem->batch : "";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $wpp = $this->message->wpp;

        
        $body = [
            "phone"=> $this->message->phone,
            "message"=> $this->message->body,
            "isGroup"=> $this->message->group == 1 ? true : false
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
            $data['phone'] = $this->message->phone;
            $data['status'] = "ENVIADO";

                // A solicitação foi bem-sucedida
                // Faça algo com os dados

            $this->message->update($data);

            if($this->batch !== null){
                $n = $this->batch->status / 100 * count(json_decode($this->batch->body, true)) + 1;
                $this->batch->status = $n / count(json_decode($this->batch->body, true)) * 100;
                $this->batch->save();
            }
                
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

                $data['status'] = "ERRO";

                $this->message->update($data);
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                $data['status'] = "ERRO";
                $this->message->update($data);
            }
        }
    }
}
