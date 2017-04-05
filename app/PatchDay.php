<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class PatchDay extends Model
{
    protected $fillable = [
      'cost', 'start_date', 'active',
    ];

    protected $dates = ['start_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * return the PatchDay's project
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
