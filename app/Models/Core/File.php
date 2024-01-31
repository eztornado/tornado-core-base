<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'name',
        'location',
        'mimeType',
        'users_id',
        'is_public'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }

}
