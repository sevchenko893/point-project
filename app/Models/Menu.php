<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'base_price',
        'description',
        'status',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
    ];
    /**
     * Relasi ke MenuVariant (1 Menu punya banyak variasi)
     */
    public function variants()
    {
        return $this->hasMany(MenuPriceOption::class);
    }

    /**
     * Relasi ke TransactionItem (1 menu bisa muncul di banyak transaksi)
     */
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function priceOptions()
    {
        return $this->hasMany(MenuPriceOption::class);
    }
}