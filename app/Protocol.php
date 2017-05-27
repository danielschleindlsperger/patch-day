<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    protected $attributes = [
        'done' => false,
    ];

    // protocol number is set in event listener
    protected $fillable = [
        'comment', 'done', 'due_date', 'protocol_number', 'patch_day_id',
    ];

    protected $casts = [
        'done' => 'boolean',
        'due_date' => 'date',
        'protocol_number' => 'integer',
        'patch_day_id' => 'integer',
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
}
