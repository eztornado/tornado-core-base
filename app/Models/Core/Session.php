<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions';

    protected $fillable = [
        'users_id',
        'validation_token',
        'ip',
        'client',
        'is_alive',
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }

}
