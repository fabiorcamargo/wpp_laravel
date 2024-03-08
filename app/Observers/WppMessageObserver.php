<?php

namespace App\Observers;

use App\Models\WppMessage;

class WppMessageObserver
{
    /**
     * Handle the WppMessage "created" event.
     */
    public function created(WppMessage $wppMessage): void
    {
        //
    }

    /**
     * Handle the WppMessage "updated" event.
     */
    public function updated(WppMessage $wppMessage): void
    {
        //
    }

    /**
     * Handle the WppMessage "deleted" event.
     */
    public function deleted(WppMessage $wppMessage): void
    {
        //
    }

    /**
     * Handle the WppMessage "restored" event.
     */
    public function restored(WppMessage $wppMessage): void
    {
        //
    }

    /**
     * Handle the WppMessage "force deleted" event.
     */
    public function forceDeleted(WppMessage $wppMessage): void
    {
        //
    }
}
