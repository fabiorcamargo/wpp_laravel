<?php

namespace App\Observers;

use App\Http\Controllers\WppRequest;
use App\Jobs\WppInstanceCreate;
use App\Jobs\WppInstanceDelete;
use App\Models\WppConnect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class WppObserver
{
    /**
     * Handle the WppConnect "created" event.
     */
    public function created(WppConnect $wppConnect): void
    {
        $request['session'] = (string) Str::orderedUuid();
        $request['status'] = 'criando';

        dispatch(new WppInstanceCreate($wppConnect));
    }

    /**
     * Handle the WppConnect "updated" event.
     */
    public function updated(WppConnect $wppConnect): void
    {
        //
    }

    /**
     * Handle the WppConnect "deleted" event.
     */
    public function deleted(WppConnect $wppConnect): void
    {
        dispatch(new WppInstanceDelete($wppConnect));
    }

    /**
     * Handle the WppConnect "restored" event.
     */
    public function restored(WppConnect $wppConnect): void
    {
        //
    }

    /**
     * Handle the WppConnect "force deleted" event.
     */
    public function forceDeleted(WppConnect $wppConnect): void
    {
        //
    }
}
