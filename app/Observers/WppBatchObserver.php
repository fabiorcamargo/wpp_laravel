<?php

namespace App\Observers;

use App\Jobs\WppInstanceMessageSend;
use App\Models\WppBatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class WppBatchObserver
{
    /**
     * Handle the WppBatch "created" event.
     */
    public function created(WppBatch $wppBatch): void
    {
        Log::info("Observer iniciado para WppBatch ID: {$wppBatch->id}");

        $body = json_decode($wppBatch->body, true);
        $wpp = $wppBatch->wpp()->first();
        $msg = $wppBatch->msg;
        $time = max($wppBatch->delay, 1); // Delay mínimo de 1 segundo

        if (empty($body) || count($body) <= 1) {
            Log::warning("WppBatch ID {$wppBatch->id} ignorado: corpo da mensagem vazio ou incompleto.");
            return;
        }

        $colunas = $body[0]; // Cabeçalhos das colunas

        foreach ($body as $index => $linha) {
            if ($index === 0) continue; // Pula cabeçalhos

            // Substituindo placeholders na mensagem
            $mensagem_completa = $msg;
            foreach ($linha as $key => $valor) {
                $mensagem_completa = str_replace("{" . $colunas[$key] . "}", $valor, $mensagem_completa);
            }

            // Formatando telefone
            $phone = preg_replace('/\D/', '', $linha[1]); 
            if (strlen($phone) < 10) {
                $phone = "55" . $phone; 
            }

            // Criando a mensagem no banco
            try {
                $data = [
                    'phone' => $phone,
                    'type' => 'chat',
                    'body' => $mensagem_completa,
                    'group' => false
                ];

                Log::info("Criando mensagem para {$phone} com dados:", $data);
                
                $mensagem = $wpp->Messages()->create($data);
                $mensagem->batch = $wppBatch;
            } catch (\Exception $e) {
                Log::error("Erro ao criar mensagem no banco para {$phone}: " . $e->getMessage());
                continue;
            }

            // Verificando se a conexão de fila está configurada corretamente
            $queueConnection = config('queue.default');
            if (empty($queueConnection) || $queueConnection === 'sync') {
                Log::error("Conexão de fila inválida: {$queueConnection}. Verifique o .env.");
                continue;
            }

            // Disparando a job na fila
            try {
                Log::info("Enfileirando mensagem para {$phone} com delay de {$time} segundos...");
                dispatch(new WppInstanceMessageSend($mensagem))->delay($time);
            } catch (\Exception $e) {
                Log::error("Erro ao enfileirar mensagem para {$phone}: " . $e->getMessage());
            }

            // Incrementando delay para a próxima mensagem
            $time += $wppBatch->delay;
        }
    }
}
