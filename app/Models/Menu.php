<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "restaurant.menu";
    protected $fillable = [
        'category_id',
        'category_name',
        'item_name',
        'price',
        'image'
    ];
    public $timestamps = false;
}
