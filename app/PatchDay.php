<?php

namespace App;

use App\Project;
use Carbon\Carbon;
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
     * return the patch-day's protocols
     */
    public function protocols()
    {
        return $this->hasMany(Protocol::class);
    }

    /**
     * Get the date in actual date format
     *
     * @param string $value
     *
     * @return string
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }
}
