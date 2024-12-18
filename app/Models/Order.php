<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'restaurant.orders';
    protected $fillable = [
        'order_id',

    ];

    public $timestamps = false;
}
