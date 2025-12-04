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
            [
                'name' => 'Espresso / Doppio / Hot Long Black',
                'category_id' => 1,
                'base_price' => 15000,
                'photo_path' => 'data/hot_long_black.png',
            ],
            [
                'name' => 'Latte',
                'category_id' => 1,
                'base_price' => 20000,
                'photo_path' => 'data/latte.png',
            ],
            [
                'name' => 'Cappuccino',
                'category_id' => 1,
                'base_price' => 20000,
                'photo_path' => 'data/cappuccino.png',
            ],
            [
                'name' => 'Mocha',
                'category_id' => 1,
                'base_price' => 20000,
                'photo_path' => 'data/latte.png', // kalau ada file mocha beri tau
            ],
            [
                'name' => 'Caramel Macchiato',
                'category_id' => 1,
                'base_price' => 20000,
                'photo_path' => 'data/caramel_macchiato.png',
            ],

            // Non-Coffee
            [
                'name' => 'Shaken Blackpeach / Lychee',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/shaken_blackpeach.png',
            ],
            [
                'name' => 'Shaken Creamy Latte',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/shaken_creamy_latte.png',
            ],
            [
                'name' => 'Palm Sugar Latte',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/ice_palm_suger_latte.png',
            ],
            [
                'name' => 'Pistachio Latte',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/pistachio_latte.png',
            ],
            [
                'name' => 'Matcha Latte',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/hot_matcha_latte.png',
            ],
            [
                'name' => 'Kiwi Pandan',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/kiwi_pandan.png',
            ],
            [
                'name' => 'Mysterious Green Frappe',
                'category_id' => 2,
                'base_price' => 30000,
                'photo_path' => 'data/mysterious_green_frappe.png',
            ],
            [
                'name' => 'Crunchy Caramel Frappe',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/crunchy_caramel_freppe.png',
            ],
            [
                'name' => 'Signature Chocolate',
                'category_id' => 2,
                'base_price' => 25000,
                'photo_path' => 'data/signature_chocolate.png',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu + ['status' => 'available']);
        }
    }
}
