<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugar extends Model
{
    use HasFactory;
    protected $table = 'sugar_levels'; // 👈 nama tabel custom

    protected $fillable = [
        'name',
        'price',
    ];
}
