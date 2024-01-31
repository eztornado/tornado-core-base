<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'additional_data'
    ];

}
