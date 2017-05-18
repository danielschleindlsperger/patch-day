<?php

namespace App\Observers;

use App\Protocol;

class ProtocolObserver
{
    /**
     * Listen to the Prtocol created event.
     *
     * @param  Protocol $protocol
     * @return void
     */
    public function created(Protocol $protocol)
    {
        if ($protocol->patchDay) {
            // update the protocol with a count index inside the patch-day
            $count = $protocol->patchDay->protocols()->count();
            $protocol->protocol_number = $count;
            $protocol->save();
        }
    }
}