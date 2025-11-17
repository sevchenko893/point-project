<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
{
    DB::table('categories')->insert([
        [
            'name' => 'Coffee',
            'description' => 'Menu berbasis kopi.',
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'name' => 'Non-Coffee',
            'description' => 'Menu tanpa kopi.',
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    }
}
