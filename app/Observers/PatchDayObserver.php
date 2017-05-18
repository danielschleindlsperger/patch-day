<?php

namespace App\Observers;

use App\PatchDay;
use App\Services\GenerateProtocolsService;
use Illuminate\Support\Facades\Log;

class PatchDayObserver
{
    /**
     * Listen to the PatchDay saved event.
     *
     * @param  PatchDay $patchDay
     * @return void
     */
    public function saved(PatchDay $patchDay)
    {
        // update protocols for patch-day if necessary
        (new GenerateProtocolsService($patchDay->id))->go();
    }
}