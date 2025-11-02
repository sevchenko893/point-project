<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu
        $menus = Menu::all();
        return view('menu.index', compact('menus'));
    }

    public function show($id)
    {
        // Detail menu
        $menu = Menu::findOrFail($id);
        return view('menu.show', compact('menu'));
    }
}