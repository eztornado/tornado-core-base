<?php

namespace App\Models\Payments;

use App\Models\Core\User;
use App\Traits\Observable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;

class UserBillable extends User
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    protected $table = 'users';
    protected $appends = ['hasPaymentMethod'];

    public function getHasPaymentMethodAttribute() : bool {
        return sizeof($this->paymentMethods()) > 0;
    }

    public function credits()
    {
        return $this->hasOne(UserBillableCredits::class,'users_id');
    }

}
