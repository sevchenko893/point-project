<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Kopi Latte', 'description' => 'Espresso dengan susu segar', 'status' => 'available'],
            ['name' => 'Teh Tarik', 'description' => 'Teh dengan susu kental manis', 'status' => 'available'],
            ['name' => 'Matcha Latte', 'description' => 'Teh hijau Jepang dengan susu', 'status' => 'available'],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}