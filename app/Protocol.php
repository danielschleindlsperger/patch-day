<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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

        // protocols that are older than this one
        $protocols = $this->project->protocols()
            ->orderBy('id', 'DESC')
            ->where('id', '<', $this->id)
            ->get();

        $missedProtocols = 0;
        $protocols->each(function ($protocol) use (&$missedProtocols) {
            if ($protocol->done) {
                return false;
            }
            $missedProtocols++;
        });

        $price = $base_price + ($penalty * $missedProtocols);

        return $price;
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
            function($query) use ($protocolId, $projectId) {
                $query->select('technology_id')
                    ->from('project_technology')
                    ->where('protocol_id', '=', $protocolId)
                    ->where('project_id', '=', $projectId);
        })->get();

        return $upgrades;
    }
}
