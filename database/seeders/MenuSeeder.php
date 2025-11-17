<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Coffee
            ['name' => 'Espresso / Doppio / Hot Long Black', 'category_id' => 1, 'base_price' => 15000],
            ['name' => 'Latte', 'category_id' => 1, 'base_price' => 20000],
            ['name' => 'Cappuccino', 'category_id' => 1, 'base_price' => 20000],
            ['name' => 'Mocha', 'category_id' => 1, 'base_price' => 20000],
            ['name' => 'Caramel Macchiato', 'category_id' => 1, 'base_price' => 20000],

            // Non-Coffee
            ['name' => 'Shaken Blackpeach / Lychee', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Shaken Creamy Latte', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Palm Sugar Latte', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Pistachio Latte', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Matcha Latte', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Kiwi Pandan', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Mysterious Green Frappe', 'category_id' => 2, 'base_price' => 30000],
            ['name' => 'Crunchy Caramel Frappe', 'category_id' => 2, 'base_price' => 25000],
            ['name' => 'Signature Chocolate', 'category_id' => 2, 'base_price' => 25000],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu + ['status' => 'available']);
        }
    }
}
