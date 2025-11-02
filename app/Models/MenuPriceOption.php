<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPriceOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'temperature',
        'size',
        'price',
    ];

    /**
     * Relasi ke Menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Scope untuk mencari harga berdasarkan suhu dan ukuran
     */
    public function scopeOfType($query, $temperature, $size)
    {
        return $query->where('temperature', $temperature)
                     ->where('size', $size);
    }
}