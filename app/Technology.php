<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Technology extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'version',
    ];

    /**
     * get all patch days this exact technology is used in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function patchDays()
    {
        return $this->belongsToMany(PatchDay::class);
    }
}
