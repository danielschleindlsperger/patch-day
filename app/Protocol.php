<?php

namespace App;

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

    protected $dates = ['due_date'];

    protected $casts = [
        'done' => 'boolean',
        'protocol_number' => 'integer',
        'patch_day_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * return the protocol's patch day
     */
    public function patchDay()
    {
        return $this->belongsTo(PatchDay::class);
    }
}
