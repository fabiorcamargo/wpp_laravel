<?php

namespace App\Observers;

use App\Jobs\WppInstanceMessageSend;
use App\Models\WppBatch;

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

        // Pegando os nomes das colunas
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
            $phone = strlen($linha[1]) < 10 ? "55" . $linha[1] : $linha[1];

            // Preparando os dados para a criação da mensagem
            $data = [
                'phone' => $phone,
                'type' => 'chat',
                'body' => $mensagem_completa,
                'group' => false
            ];

            $mensagem = $wpp->Messages()->create($data);
            $mensagem->batch = $wppBatch;

            // Disparando o envio da mensagem
            dispatch(new WppInstanceMessageSend($mensagem, $wppBatch))->delay($time);
            
            

            // Aumentando o tempo de delay para a próxima mensagem
            $time = $time + $wppBatch->delay;
        }
    }

    /**
     * Handle the WppBatch "updated" event.
     */
    public function updated(WppBatch $wppBatch): void
    {
        //
    }

    /**
     * Handle the WppBatch "deleted" event.
     */
    public function deleted(WppBatch $wppBatch): void
    {
        //
    }

    /**
     * Handle the WppBatch "restored" event.
     */
    public function restored(WppBatch $wppBatch): void
    {
        //
    }

    /**
     * Handle the WppBatch "force deleted" event.
     */
    public function forceDeleted(WppBatch $wppBatch): void
    {
        //
    }
}
