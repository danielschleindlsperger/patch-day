<?php

namespace App\Observers;

use App\Protocol;

class ProtocolObserver
{
    /**
     * Listen to the Protocol created event.
     *
     * @param  Protocol $protocol
     * @return void
     */
    public function updated(Protocol $protocol)
    {
        // set patch-day status
        $patch_day = $protocol->patch_day;
        if (!$patch_day) return;

        if ($patch_day->protocolsDone()) {
            $patch_day->status = 'done';
        }

        $patch_day->save();
    }
}
