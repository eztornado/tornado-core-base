<?php

namespace App\Models\Payments;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;

class UserBillableCredits extends Model
{
    protected $table = 'users_credits';

    protected $fillable = [
        'users_id',
        'credits'
    ];

    public function user()
    {
        return $this->belongsTo(UserBillable::class,'users_id');
    }

}
