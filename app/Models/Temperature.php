<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temperature extends Model
{
    use HasFactory;
    protected $table = 'temperature_options'; // 👈 nama tabel custom
    protected $fillable = [
        'name',
        'price',
    ];

}
