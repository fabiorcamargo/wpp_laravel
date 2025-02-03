<?php

namespace App\Jobs;

use App\Models\User;
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
use Laravel\Sanctum\PersonalAccessToken;

class WppInstanceMessageSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $batch;
    protected $token;
    protected $url;
    protected $type;
    protected $text;
    protected $img;
    protected $body;

    /**
     * Create a new job instance.
     */
    public function __construct($mensagem)
    {
        $this->message = $mensagem;
        $this->type = isset(json_decode($mensagem->body)->img) && json_decode($mensagem->body)->img !== "" ? "img" : "text";
        //dd(json_decode($mensagem->body));
        // Corrigindo a criação do corpo com array ao invés de string JSON
        if ($this->type == "img") {
            // Certificando-se de que a URL e o caption estão corretamente formatados
            $this->body = [
                'jid' => $this->message->phone,
                'type' => 'number',
                'message' => [
                    'image' => [
                        'url' => json_decode($this->message->body)->img
                    ],
                    'caption' => json_decode($this->message->body)->text,
                ],
                'options' => ['quoted' => null]
            ];
        } else {
            // Para mensagens de texto, garantindo a estrutura correta
            $this->body = [
                'jid' => $this->message->phone,
                'message' => [
                    'text' => json_decode($this->message->body)->text
                ]
            ];
        }
        dd($this->body);

        // Condicional para definir a batch
        $mensagem->batch !== null ? $this->batch = $mensagem->batch : "";

        // Obtenção do usuário associado ao WppConnect
        $wpp = $mensagem->wpp()->first();
        $id = $wpp->user_id;
        $user = User::find($id);

        // Configuração da URL da API do WhatsApp
        $this->url = $user->url_api . '/' . $wpp->session  . '/messages/send';

        // Obtendo o token de acesso do usuário
        $this->token = PersonalAccessToken::where('tokenable_id', $user->id)->first()->token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Envio da requisição HTTP com cabeçalhos e corpo
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $this->token
            ])->post($this->url, $this->body); // Passando o corpo como array diretamente

            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response, true);

                // Atribuindo o ID da mensagem no WhatsApp e o status
                $data['wppid'] = $data['key']['id'];
                $data['status'] = "ENVIADO";

                // Atualizando o status da mensagem no banco de dados
                $this->message->update($data);

                // Atualizando o progresso da batch, caso exista
                if ($this->batch !== null) {
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
                echo "Erro na solicitação: Status $statusCode, Response: $errorBody";

                // Atualizando o status de erro da mensagem
                $data['status'] = "ERRO";
                $this->message->update($data);
            } else {
                // Lidar com outros tipos de erros (por exemplo, problemas de rede)
                echo "Erro na solicitação: " . $e->getMessage();

                // Atualizando o status de erro da mensagem
                $data['status'] = "ERRO";
                $this->message->update($data);
            }
        }
    }
}
