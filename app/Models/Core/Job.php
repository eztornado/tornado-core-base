<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';

    protected $fillable = [
        'name',
        'param1',
        'param2',
        'param3',
        'time',
        'command',
        'active'
    ];

}
