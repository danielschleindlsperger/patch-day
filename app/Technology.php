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

    protected $appends = [
        'canonical_name',
    ];

    /**
     * get all projects this exact technology is used in.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withPivot('protocol_id');
    }

    public function getDateAttribute()
    {
        return $this->pivot ?
            Protocol::find($this->pivot->protocol_id)->date : null;
    }

    public function getCanonicalNameAttribute()
    {
        return $this->name . ' ' . $this->version;
    }
}
