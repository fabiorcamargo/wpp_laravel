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
        $time = 3;
        $wpp = $wppBatch->wpp()->first();       

        foreach ($body as $send) {
            $phone = strlen($send[1]) < 11 ? "55" . $send[1] : $send[1];
            $data = [
                'phone' => $phone,
                'type' => 'chat',
                'body' => $msg,
                'group' => false
            ];
            
            $mensagem = $wpp->Messages()->create($data);

            $mensagem->batch = $wppBatch;

            //dd($mensagem);

            
            dispatch(new WppInstanceMessageSend($mensagem))->delay($time);
            
            $time = $time + 3;
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
