<?php

namespace App;

use App\Project;
use Illuminate\Database\Eloquent\Model;

class PatchDay extends Model
{
    protected $fillable = [
      'date',
    ];

    protected $dates = ['date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     *
     * return the patchDay's protocols
     */
    public function protocols()
    {
        return $this->hasMany(Protocol::class);
    }
}
