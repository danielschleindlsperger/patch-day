<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

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
        'default_technologies',
        'current_technologies',
        'technology_history',
    ];

    protected $hidden = [
        'technology_history',
        'technologies',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'base_price' => 'double',
        'penalty' => 'double',
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
     * return the project's technologies
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class)
            ->withPivot('action', 'protocol_id')->withTimestamps();
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

    public function patchDays()
    {
        $project_id = $this->id;

        return PatchDay::whereHas('protocols', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->get();
    }

    /**
     * return only the latest version for each technology for each
     * technology (based on name).
     *
     * @return Collection technologies
     */
    public function getCurrentTechnologiesAttribute()
    {
        $deletedTechs = $this->technologies()->where('action', '=', 'deleted')
            ->groupBy('name')->get();

        $deletedTechs = $deletedTechs->map(function ($tech) {
            return $tech->name;
        })->toArray();

        return $this->technologies()
            ->orderBy('protocol_id', 'DESC')
            ->groupBy('name')
            ->whereNotIn('name', $deletedTechs)
            ->orderBy('name', 'ASC')
            ->get();
    }

    /**
     * return all technologies the project has ever had.
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

    /**
     * The technologies the project has by default before all patches.
     *
     * @return mixed
     */
    public function getDefaultTechnologiesAttribute()
    {
        return $this->technologies()
            ->where('action', '=', 'default')
            ->orderBy('name', 'ASC')
            ->orderBy('version', 'DESC')
            ->get();
    }
}
