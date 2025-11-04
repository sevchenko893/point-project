<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'table_number',
        'device_token',
        'menu_id',
        'temperature',
        'size',
        'ice_level',
        'sugar_level',
        'quantity',
        'price',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
