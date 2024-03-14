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

        $url = env('URL_API') . '/message/sendText/' . $wpp->session;

        $body = [
            "number"=> $this->message->phone,
            "options" => [
                "delay"=> 1200,
                "presence"=> "composing",
                "linkPreview"=> false
            ],
            "textMessage" => [
                "text" => $this->message->body
            ]
        ];

        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'apikey' => env('WPP_KEY')
            ])->post($url, $body);

            //dd(json_decode($response, true));


            // Verifique o status da resposta
            if ($response->getStatusCode() === 201) {

            $data = json_decode($response, true);
            //dd($data);

            $data['wppid'] = $data['key']['id'];
            $data['phone'] = $data['key']['remoteJid'];
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
                
                $data['status'] = "ERRO";

                $this->message->update($data);
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
