<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo'
    ];

    protected $attributes = [
      'logo' => '/img/placeholder_logo.png',
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
     * return the companies projects
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Return all users that belong to the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Prepend relevant folder names
     *
     * @param string
     *
     * @return string
     */
    public function getLogoAttribute($path)
    {
        $exists = Storage::disk('public')->exists($path);

        return $exists ? Storage::url($path) : $path;
    }

}
