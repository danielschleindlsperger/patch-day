<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Protocol extends Model
{
    protected $attributes = [
        'done' => false,
    ];

    protected $appends = [
        'date',
        'price',
        'technology_updates',
    ];

    protected $fillable = [
        'comment', 'done', 'patch_day_id', 'project_id',
    ];

    protected $with = [
        'project',
        'patch_day',
    ];

    protected $casts = [
        'done' => 'boolean',
        'patch_day_id' => 'integer',
        'project_id' => 'integer',
        'date' => 'date',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * return the protocol's project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * get the protocol's patch-day
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patch_day()
    {
        return $this->belongsTo(PatchDay::class);
    }

    /**
     * Get the protocols date from it's patch-day
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return $this->patch_day->date;
    }

    /**
     * Determine the price for the protocol.
     * Missed patch-days imply a penalty for each missed one.
     *
     * @return int price
     */
    public function getPriceAttribute()
    {
        $base_price = $this->project->base_price;
        $penalty = $this->project->penalty;
        $protocol = $this;
        $protocolPatchDay = $this->patch_day;

        $projectPreviousPatchDay =
            $this->project->patchDays()
                ->filter(function ($patch_day) use ($protocolPatchDay) {
                    return $patch_day->date < $protocolPatchDay->date;
                })
                ->sortByDesc('date')
                ->first();

        $missedProtocols = 0;

        if ($projectPreviousPatchDay) {

            // previous patch-days
            $patch_days = PatchDay::whereDate('date', '<', $protocolPatchDay->date)
                ->whereDate('date', '>', $projectPreviousPatchDay->date)
                ->orderBy('id', 'DESC')
                ->get();

            $patch_days->each(function ($patch_day) use (&$missedProtocols, $protocol) {

                $patchDayProtocols = $patch_day->protocols()->get();

                $protocolInPatchDay = $patchDayProtocols->contains(function ($value) use ($protocol) {
                    return $value->id === $protocol->id;
                });

                if ($protocolInPatchDay) {
                    return false;
                }

                $missedProtocols++;
            });
        }

        return $base_price + ($penalty * $missedProtocols);
    }

    /**
     * Get all the Technologies that were upgraded with this protocol.
     *
     * @return mixed
     */
    public function getTechnologyUpdatesAttribute()
    {
        $protocolId = $this->id;
        $projectId = $this->project->id;

        $upgrades = Technology::whereIn('id',
            function ($query) use ($protocolId, $projectId) {
                $query->select('technology_id')
                    ->from('project_technology')
                    ->where('protocol_id', '=', $protocolId)
                    ->where('project_id', '=', $projectId);
            })->get();

        return $upgrades;
    }

    /**
     * Set the projects technologies that are updated with this protocol.
     *
     * @param array $newTechs
     */
    public function syncTechnologies($newTechs)
    {
        $currentTechs = DB::table('project_technology')
            ->where('protocol_id', '=', $this->id)
            ->get();

        $techIds = $currentTechs->map(function ($tech) {
            return (int)$tech->technology_id;
        });

        $this->project->technologies()->detach($techIds);
        $this->project->technologies()->attach($newTechs, [
            'protocol_id' => $this->id,
            'action' => 'update',
        ]);
    }
}
