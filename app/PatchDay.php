<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class PatchDay extends Model
{
    protected $fillable = [
      'cost', 'start_date', 'interval', 'active', 'project_id'
    ];

    protected $dates = ['start_date'];

    protected $casts = [
        'cost' => 'integer',
        'active' => 'boolean',
        'project_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * return the PatchDay's project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * return the patchDay's protocols
     */
    public function protocols()
    {
        return $this->hasMany(Protocol::class);
    }

    /**
     * The patch day's technologies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }
}
