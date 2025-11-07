<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Temperature Options
        DB::table('temperature_options')->insert([
            ['name' => 'Hot', 'extra_price' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ice', 'extra_price' => 5000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 🔹 Sugar Levels
        DB::table('sugar_levels')->insert([
            ['name' => 'Normal', 'extra_price' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Less Sugar', 'extra_price' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 🔹 Sizes
        DB::table('sizes')->insert([
            ['name' => 'Normal', 'extra_price' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Large', 'extra_price' => 5000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
