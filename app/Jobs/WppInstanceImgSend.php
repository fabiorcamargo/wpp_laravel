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

class WppInstanceImgSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $message;


        public function __construct($mensagem)
    {
        $this->message = $mensagem;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $wpp = $this->message->wpp;

        $url = env('URL_API') . '/message/sendMedia/' . $wpp->session;

        $body = [
            "number" => $this->message->phone,
            "options" => [
                "delay" => 1200,
                "presence" => "composing",
            ],
            "mediaMessage" => [
                "mediatype" => "image",
                "caption" => $this->message->body,
                "media" => $this->message->img
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


            } else {

                $data['status'] = "ERRO";
                $this->message->update($data);

            }
        } catch (RequestException $e) {
            // Captura exceções do Guzzle
            if ($e->hasResponse()) {

                $data['status'] = "ERRO";
                $this->message->update($data);

            } else {

                $data['status'] = "ERRO";
                $this->message->update($data);
            }
        }
    }
}
