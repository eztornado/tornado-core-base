<?php

namespace App\Models\Payments;

use Illuminate\Database\Eloquent\Model;

class CreditsPlan extends Model
{
    protected $table = 'credits_plans';

    protected $fillable = [
        'name',
        'credits',
        'price',
        'stripe_product',
        'description',
        'icon'
    ];

}
