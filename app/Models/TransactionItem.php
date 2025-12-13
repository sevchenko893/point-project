<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'menu_id',
        'temperature',
        'size',
        'ice_level',
        'sugar_level',
        'quantity',
        'price',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function sizeOption()
    {
        return $this->belongsTo(Size::class, 'size');
    }

    public function temperatureOption()
    {
        return $this->belongsTo(Temperature::class, 'temperature');
    }

    public function sugarOption()
    {
        return $this->belongsTo(Sugar::class, 'sugar_level');
    }
}
