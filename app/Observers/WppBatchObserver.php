<?php

namespace App\Observers;

use App\Jobs\WppInstanceImgSend;
use App\Jobs\WppInstanceMessageSend;
use App\Jobs\WppSendMessageBatch;
use App\Models\WppBatch;
use Carbon\Carbon;



class WppBatchObserver
{

    public $time = 5;

    /**
     * Handle the WppBatch "created" event.
     */
    public function created(WppBatch $wppBatch): void
    {
        $body = json_decode($wppBatch->body);
        $wpp = $wppBatch->wpp()->first();
        $msg = $wppBatch->msg;

        $wpp = $wppBatch->wpp()->first();

        foreach ($body as $key => $send) {
            $msg = $wppBatch->msg;
            $time = $send->delay;

            // Criando um objeto DateTime representando o momento atual
            $now = Carbon::now();

            // Adicionando o tempo de atraso (15 segundos) ao momento atual
            $delayedTime = $now->addSeconds($this->time);

            //dd($time);
            foreach ((array)$send as $chave => $valor) {
                $marcador = '{{' . $chave . '}}';
                $msg = str_replace($marcador, $valor, $msg);
            }

            $phone = strlen($send->Telefone) < 11 ? "55" . $send->Telefone : $send->Telefone;

            if ($send->type == 'Texto') {
                $data = [
                    'phone' => $phone,
                    'type' => 'chat',
                    'body' => $msg,
                    'group' => false
                ];
                $mensagem = $wpp->Messages()->create($data);

                $mensagem->batch = $wppBatch;

                dispatch(new WppInstanceMessageSend($mensagem))->delay($delayedTime);
            } else if ($send->type == 'Imagem') {
                $data = [
                    'phone' => $phone,
                    'type' => 'chat',
                    'body' => $msg,
                    'img' => $send->img,
                    'group' => false
                ];

                $mensagem = $wpp->Messages()->create($data);

                $mensagem->batch = $wppBatch;

                dispatch(new WppInstanceImgSend($mensagem))->delay($delayedTime);
            }

            $this->time = $this->time + $time;
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
