<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Temperature;
use App\Models\Size;
use App\Models\Sugar;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    public function index()
    {
        // Ambil semua menu
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        $temperatures = Temperature::all();
        $sizes = Size::all();
        $sugars = Sugar::all();
        $categories = Category::all();

        return view('menu.show', compact('menu', 'temperatures', 'sizes', 'sugars','categories'));
    }

    // ================= CRUD ADMIN =================

    public function create()
    {
        $categories = Category::all();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        Menu::create($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        dd($request);

        $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        $menu->update($request->all());

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
