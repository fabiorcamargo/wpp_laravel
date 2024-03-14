<?php

namespace App\Observers;

use App\Jobs\WppInstanceMessageSend;
use App\Jobs\WppSendMessageBatch;
use App\Models\WppBatch;

class WppBatchObserver
{
    /**
     * Handle the WppBatch "created" event.
     */
    public function created(WppBatch $wppBatch): void
    {
        $body = json_decode($wppBatch->body);


        $wpp = $wppBatch->wpp()->first();
        $msg = $wppBatch->msg;
        $time = 5;
        $wpp = $wppBatch->wpp()->first();

        foreach ($body as $key => $send) {
            $msg = $wppBatch->msg;
            foreach ((array)$send as $chave => $valor) {
                    $marcador = '{{' . $chave . '}}';
                    $msg = str_replace($marcador, $valor, $msg);
                }

            $phone = strlen($send->Telefone) < 11 ? "55" . $send->Telefone : $send->Telefone;
            $data = [
                'phone' => $phone,
                'type' => 'chat',
                'body' => $msg,
                'group' => false
            ];

            $mensagem = $wpp->Messages()->create($data);

            $mensagem->batch = $wppBatch;

            dispatch(new WppInstanceMessageSend($mensagem))->delay($time);

            $time = $time + 5;
        }
    }

    public function updated(WppBatch $wppBatch): void
    {

    }

    public function deleted(WppBatch $wppBatch): void
    {
        //
    }

    public function restored(WppBatch $wppBatch): void
    {
        //
    }

    public function forceDeleted(WppBatch $wppBatch): void
    {
        //
    }
}
