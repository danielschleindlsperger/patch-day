<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Protocol extends Model
{
    protected $fillable = [
        'comment', 'done', 'due_date',
    ];

    protected $dates = ['due_date'];

    protected $casts = ['done' => 'boolean'];
}
