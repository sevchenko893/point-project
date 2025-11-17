<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Temperature;
use App\Models\Size;
use App\Models\Sugar;
use App\Models\Category;
use Illuminate\Http\Request;

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

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menus.edit', compact('menu','categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price'  => 'required|numeric',
            'description' => 'nullable|string',
            'status'      => 'required|in:available,unavailable',
        ]);

        Menu::create($request->only(['name','category_id','base_price','description','status']));

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price'  => 'required|numeric',
            'description' => 'nullable|string',
            'status'      => 'required|in:available,unavailable',
        ]);

        $menu->update($request->only(['name','category_id','base_price','description','status']));

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui!');
    }


}
