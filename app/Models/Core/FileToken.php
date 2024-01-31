<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class FileToken extends Model
{
    protected $table = 'files_tokens';

    protected $fillable = [
        'files_id',
        'users_id',
        'token',
        'expires_at'
    ];

    public function file()
    {
        return $this->belongsTo(File::class,'files_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }

}
