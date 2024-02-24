<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;


class Credential extends Model
{
    protected $table = 'credentials';
    protected $fillable = [
        'name',
        'http_header',
        'daily_usage',
        'daily_usage_limit',
        'monthly_usage',
        'monthly_usage_limit',
    ];

}
