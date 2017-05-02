<?php

namespace App;

use App\PatchDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'company_id',
    ];

    protected $casts = [
        'company_id' => 'integer',
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
     * return the project's PatchDays
     */
    public function patchDay()
    {
        return $this->hasOne(PatchDay::class);
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
}
