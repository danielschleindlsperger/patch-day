<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PatchDay extends Model
{
    protected $fillable = [
        'date',
        'status',
    ];

    protected $dates = ['date'];

    protected $appends = [
        'name',
    ];

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

    /**
     * Get all projects that are registered for this patch-day.
     *
     * @return Collection projects
     */
    public function getProjectsAttribute()
    {
        $patch_day_id = $this->id;

        return Project::whereHas('protocols', function ($query) use ($patch_day_id) {
            $query->where('patch_day_id', $patch_day_id);
        })->get();
    }

    /**
     * PatchDay has a name like this: PatchDay|March2017
     */
    public function getNameAttribute()
    {
        $carbon = Carbon::parse($this->date);
        $month  = $carbon->format('F');
        $year   = $carbon->format('Y');

        return "PatchDay {$month} {$year}";
    }

    /**
     * Check if all the patch-days protocols are done.
     *
     * @return bool
     */
    public function protocolsDone()
    {
        $todoProtocols = $this->protocols()->where('done', '=', false)->count();

        return $todoProtocols === 0;
    }
}
