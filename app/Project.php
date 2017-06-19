<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'company_id', 'base_price', 'penalty',
    ];

    protected $with = [
        'technologies',
    ];

    protected $appends = [
        'current_technologies',
        'technology_history',
    ];

    protected $hidden = [
        'technology_history',
        'technologies',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'base_price' => 'integer',
        'penalty' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * return the project's Protocols
     */
    public function protocols()
    {
        return $this->hasMany(Protocol::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * return the project's technologies
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class)->withPivot('protocol_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * return the company the project belongs to
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * return only the latest version for each technology for each unique
     * technlogy (based on name).
     *
     * @return Collection technologies
     */
    public function getCurrentTechnologiesAttribute()
    {
        return $this->technologies()
            ->orderBy('name', 'ASC')
            ->orderBy('protocol_id', 'DESC')
            ->get()
            ->unique('name')
            ->values();
    }

    /**
     * return all
     *
     * @return Collection technologies
     */
    public function getTechnologyHistoryAttribute()
    {
        return $this->technologies()
            ->orderBy('protocol_id', 'DESC')
            ->orderBy('name', 'ASC')
            ->orderBy('version', 'DESC')
            ->get();
    }
}
