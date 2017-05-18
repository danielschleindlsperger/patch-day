<?php

namespace App\Services;

use App\PatchDay;
use App\Protocol;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateProtocolsService
{
    /**
     *  The passed PatchDay instance
     *
     * @var PatchDay
     */
    protected $patchDay;

    /**
     * The passed PatchDay's protocols
     *
     * @var Collection
     */
    protected $protocols;

    /**
     * @var Protocol
     */
    protected $latestProtocol;

    public function __construct($id)
    {
        $this->patchDay = PatchDay::with('project', 'protocols')->find($id);
        $this->protocols = $this->patchDay->protocols;

        $this->getLatest();
    }

    /**
     * start the checks
     */
    public function go()
    {
        $needed = $this->neededProtocols(2);
        if ($needed > 0 && $this->patchDay->active &&
            $this->patchDay->start_date
        ) {
            $this->generateProtocols($needed);
            return true;
        }
    }

    /**
     * a patch-day needs new protocols if there are less done and in the
     * future than the margin suggests
     *
     * @param int $margin
     * @return mixed
     */
    protected function neededProtocols($margin)
    {
        $unfinishedProtocols =
            $this->protocols->filter(function ($protocol, $key) {
//                $inFuture = Carbon::parse($protocol->due_date)->gt(Carbon::now());
//                return $inFuture && !$protocol->done;
                return !$protocol->done;
            });
        $count = $unfinishedProtocols->count();
        return $margin - $count;
    }

    /**
     * @param int $count the amount of protocols we are gonna generate
     */
    protected function generateProtocols($count)
    {
        for ($i = 1; $i <= $count; $i++) {

            $due_date = $this->getDueDate();

            $protocol = Protocol::create([
                'patch_day_id' => $this->patchDay->id,
                'due_date' => $due_date,
            ]);

            $this->getLatest();

            Log::info('Protocol with id ' . $protocol->id . ' generated for project ' .
                $this->patchDay->project->name);
        }
    }

    /**
     * @return string
     */
    protected function getDueDate()
    {
        // return patch day's start date if it doesn't have protocols yet
        if ($this->latestProtocol && $this->latestProtocol->due_date) {
            $due_date = Carbon::parse($this->latestProtocol->due_date)
                ->addMonth($this->patchDay->interval);
        } else {
            $due_date = Carbon::parse($this->patchDay->start_date);
        }

        return $due_date->toDateString();
    }

    protected function getLatest()
    {
        $this->latestProtocol = Protocol::where([
            'patch_day_id' => $this->patchDay->id,
        ])
            ->latest('due_date')
            ->first();
    }
}