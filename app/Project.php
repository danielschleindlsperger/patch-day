<?php

namespace App;

use App\PatchDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'company_id', 'base_price', 'penalty',
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     * return the projects PatchDay
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
