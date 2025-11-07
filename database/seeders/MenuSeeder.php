<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // ☕ Coffee
            [
                'name' => 'Espresso / Doppio / Hot Long Black',
                'category' => 'coffee',
                'base_price' => 15000,
            ],
            [
                'name' => 'Latte',
                'category' => 'coffee',
                'base_price' => 20000,
            ],
            [
                'name' => 'Cappuccino',
                'category' => 'coffee',
                'base_price' => 20000,
            ],
            [
                'name' => 'Mocha',
                'category' => 'coffee',
                'base_price' => 20000,
            ],
            [
                'name' => 'Caramel Macchiato',
                'category' => 'coffee',
                'base_price' => 20000,
            ],

            // 🧋 Non-Coffee
            [
                'name' => 'Shaken Blackpeach / Lychee',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Shaken Creamy Latte',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Palm Sugar Latte',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Pistachio Latte',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Matcha Latte',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Kiwi Pandan',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Mysterious Green Frappe',
                'category' => 'non-coffee',
                'base_price' => 30000,
            ],
            [
                'name' => 'Crunchy Caramel Frappe',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
            [
                'name' => 'Signature Chocolate',
                'category' => 'non-coffee',
                'base_price' => 25000,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create([
                'name' => $menu['name'],
                'category' => $menu['category'],
                'base_price' => $menu['base_price'],
                'status' => 'available',
            ]);
        }
    }
}
