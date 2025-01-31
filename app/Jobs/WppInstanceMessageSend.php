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
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class WppInstanceMessageSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $message;
    protected $batch;
    protected $token;
    protected $url;


    public function __construct($mensagem)
    {
        $this->message = $mensagem;
        $mensagem->batch !== null ? $this->batch = $mensagem->batch : null;
    
        $wpp = $mensagem->wpp()->first();
    
        if (!$wpp) {
            // Mensagem não associada a Wpp, então precisamos abortar ou tratar
            throw new \Exception("Wpp não encontrado para a mensagem {$mensagem->id}");
        }
    
        $id = $wpp->user_id;
        $user = User::find($id);
    
        if (!$user) {
            // Usuário não encontrado
            throw new \Exception("Usuário não encontrado para Wpp {$wpp->id}");
        }
    
        $this->url = $user->url_api . $wpp->session  . '/messages/send';
        $this->token = PersonalAccessToken::where('tokenable_id', $user->id)->first()->token ?? null;
    
        if (!$this->token) {
            // Token não encontrado
            throw new \Exception("Token de acesso não encontrado para o usuário {$user->id}");
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $wpp = $this->message->wpp;

        //dd($this->message->phone);
        
        $body = [
            "jid"=> $this->message->phone,
            "message" => [
                "text" => $this->message->body
            ]
        ];

        try {
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                    'x-api-key' => $this->token
            ])->post($this->url, $body);

            //dd(json_decode($response, true));


            // Verifique o status da resposta
            if ($response->getStatusCode() === 200) {

            $data = json_decode($response, true);
            //dd($datakey);

            $data['wppid'] = $data['key']['id'];
            //$data['phone'] = $data['key']['remoteJid'];
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
