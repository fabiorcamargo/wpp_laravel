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
use Illuminate\Support\Facades\Log;

class WppInstanceMessageSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $batch;

    public $tries = 5;
    public $timeout = 120;
    public $delay = 5;

    public function __construct($mensagem)
    {
        $this->message = $mensagem;
        $this->batch = $mensagem->batch;
    }

    public function handle(): void
    {
        $wpp = $this->message->wpp;
        $url = env('URL_API') . '/message/sendText/' . $wpp->session;

        $body = [
            "number" => $this->message->phone,
            "options" => [
                "delay" => 1200,
                "presence" => "composing",
                "linkPreview" => false
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

            if ($response->getStatusCode() === 201) {
                $data = json_decode($response, true);
                $data['wppid'] = $data['key']['id'];
                $data['phone'] = $data['key']['remoteJid'];
                $data['status'] = "ENVIADO";

                $this->message->update($data);

                if ($this->batch) {
                    $totalMessages = count(json_decode($this->batch->body, true));
                    $completedMessages = $this->batch->status / 100 * $totalMessages + 1;
                    $this->batch->status = ($completedMessages / $totalMessages) * 100;
                    $this->batch->save();
                }
            } else {
                Log::error('Erro na solicitação: ' . $response->getStatusCode());
                $this->handleFailedRequest();
            }
        } catch (RequestException $e) {
            $this->handleException($e);
        }
    }

    protected function handleFailedRequest(): void
    {
        $this->message->update(['status' => 'ERRO']);
    }

    protected function handleException(RequestException $e): void
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $errorBody = $response->getBody()->getContents();
            Log::error("Erro na solicitação: Status $statusCode, Response: $errorBody");
        } else {
            Log::error("Erro na solicitação: " . $e->getMessage());
        }

        $this->handleFailedRequest();
    }

    public function tags(): array
    {
        return ['MsgSend'];
    }
}
