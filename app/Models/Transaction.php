<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'payment_method',
        'status',
    ];

    /**
     * Relasi ke TransactionItem (1 transaksi punya banyak item)
     */
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Relasi ke User (jika sistem kamu pakai authentication)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}