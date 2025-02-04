<?php

namespace App\Observers;

use App\Jobs\WppInstanceMessageSend;
use App\Models\WppBatch;
use Illuminate\Support\Facades\Log;

class WppBatchObserver
{
    /**
     * Handle the WppBatch "created" event.
     */
    public function created(WppBatch $wppBatch): void
    {
        $body = json_decode($wppBatch->body, true); // Adicionando true para retorno como array
        $wpp = $wppBatch->wpp()->first();
        $msg = $wppBatch->msg;
        $time = $wppBatch->delay;

        //dd($body);

        // Verificando se o corpo tem dados suficientes
        if (empty($body) || count($body) <= 1) {
            return; // Caso não haja dados, a execução é interrompida
        }

        // Pegando os nomes das colunas (primeira linha do body)
        $colunas = $body[0]; // Primeira linha do body contém os nomes das colunas

        // Iterando sobre os dados (começando pela segunda linha)
        foreach ($body as $index => $linha) {
            if ($index === 0) {
                continue; // Ignora a primeira linha que contém os cabeçalhos
            }

            // Fazendo a substituição dos placeholders pelos valores
            $mensagem_completa = $msg;
            foreach ($linha as $key => $valor) {
                $mensagem_completa = str_replace("{" . $colunas[$key] . "}", $valor, $mensagem_completa);
            }

            // Validando e ajustando o telefone
            $phone = preg_replace('/\D/', '', $linha[1]); // Remove qualquer caractere não numérico
            if (strlen($phone) < 10) {
                $phone = "55" . $phone; // Supondo que o número de telefone seja nacional
            }

            // Garantir que o delay seja no mínimo 1
            $time = max($time, 1);

            // Preparando os dados para a criação da mensagem
            $data = [
                'phone' => $phone,
                'type' => 'chat',
                'body' => $mensagem_completa,
                'group' => false
            ];

            //dd($data);

            $mensagem = $wpp->Messages()->create($data);
            $mensagem->batch = $wppBatch;

            // Disparando o envio da mensagem
            try {
                dispatch(new WppInstanceMessageSend($mensagem))->onQueue('zapfabio')->delay($time);
            } catch (\Exception $e) {
                // Aqui podemos logar ou tratar o erro de envio, caso necessário
                Log::error('Erro ao enviar mensagem para o número ' . $phone . ': ' . $e->getMessage());
            }

            // Aumentando o tempo de delay para a próxima mensagem
            $time = $time + $wppBatch->delay;
        }
    }

    /**
     * Handle the WppBatch "updated" event.
     */
    public function updated(WppBatch $wppBatch): void
    {
        // Lógica para quando o WppBatch for atualizado (se necessário)
    }

    /**
     * Handle the WppBatch "deleted" event.
     */
    public function deleted(WppBatch $wppBatch): void
    {
        // Lógica para quando o WppBatch for deletado (se necessário)
    }

    /**
     * Handle the WppBatch "restored" event.
     */
    public function restored(WppBatch $wppBatch): void
    {
        // Lógica para quando o WppBatch for restaurado (se necessário)
    }

    /**
     * Handle the WppBatch "force deleted" event.
     */
    public function forceDeleted(WppBatch $wppBatch): void
    {
        // Lógica para quando o WppBatch for permanentemente deletado (se necessário)
    }
}
