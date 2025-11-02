<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuPriceOption;

class MenuPriceOptionSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::all();

        foreach ($menus as $menu) {
            // Harga default bisa kamu ubah sesuai kebutuhan
            $priceBase = match($menu->name) {
                'Kopi Latte' => 15000,
                'Teh Tarik' => 12000,
                'Matcha Latte' => 18000,
                default => 10000,
            };

            // Kombinasi ukuran & suhu
            $combinations = [
                ['temperature' => 'ice', 'size' => 'normal', 'price' => $priceBase],
                ['temperature' => 'ice', 'size' => 'large', 'price' => $priceBase + 3000],
                ['temperature' => 'hot', 'size' => 'normal', 'price' => $priceBase - 1000],
                ['temperature' => 'hot', 'size' => 'large', 'price' => $priceBase + 2000],
            ];

            foreach ($combinations as $option) {
                MenuPriceOption::create([
                    'menu_id' => $menu->id,
                    'temperature' => $option['temperature'],
                    'size' => $option['size'],
                    'price' => $option['price'],
                ]);
            }
        }
    }
}