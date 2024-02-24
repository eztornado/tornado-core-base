<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;


class Endpoint extends Model
{
    protected $table = 'endpoints';
    protected $fillable = [
        'credentials_id',
        'method',
        'url',
    ];

}
