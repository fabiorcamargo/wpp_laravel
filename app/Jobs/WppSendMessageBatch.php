<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class WppSendMessageBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $send;
    protected $wpp;
    protected $msg;
    protected $wppBatch;


    public function __construct($send, $wpp, $msg, $wppBatch)
    {
        $this->send = $send;
        $this->wpp = $wpp;
        $this->msg = $msg;
        $this->$wppBatch = $wppBatch;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $body = [
            "phone" => $this->send[1],
            "message" => $this->msg,
            "isGroup" => false
        ];

        $url = 'https://api.meusestudosead.com.br/api/' . $this->wpp->session .  '/send-message';

        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->wpp->token,
            ])->post($url, $body);




            // Verifique o status da resposta
            if ($response->getStatusCode() === 201) {

                $data = $response->json()['response'][0];

                // A solicitação foi bem-sucedida
                // Faça algo com os dados

                $n = $this->wppBatch->status / 100 * count(json_decode($this->wppBatch->body, true)) + 1;
                $this->wppBatch->status = $n / count(json_decode($this->wppBatch->body, true)) * 100;
                $this->wppBatch->save();
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

                //$this->message->update($data);
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                $data['status'] = "ERRO";
                //$this->message->update($data);
            }
        }
    }
}
