<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Temperature;
use App\Models\Size;
use App\Models\Sugar;
use App\Models\Category;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu
        $menus = Menu::all();
        return view('menu.index', compact('menus'));
    }

    // public function show($id)
    // {
    //     // Detail menu
    //     $menu = Menu::findOrFail($id);
    //     return view('menu.show', compact('menu'));
    // }

        public function show($id)
    {
        $menu = Menu::findOrFail($id);
        $temperatures = Temperature::all();
        $sizes = Size::all();
        $sugars = Sugar::all();
        $sugars = Sugar::all();
        $categories = Category::all();

        return view('menu.show', compact('menu', 'temperatures', 'sizes', 'sugars','categories'));
    }
}