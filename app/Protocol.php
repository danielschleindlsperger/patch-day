<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    protected $attributes = [
        'done' => false,
    ];

    protected $fillable = [
        'comment', 'done', 'due_date',
    ];

    protected $dates = ['due_date'];

    protected $casts = ['done' => 'boolean'];

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
