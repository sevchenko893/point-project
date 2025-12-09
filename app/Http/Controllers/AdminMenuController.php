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
        $menus = Menu::whereNull('deleted_at')
        ->orderBy('created_at', 'desc')
        ->get();
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
        'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $data = $request->only(['name','category_id','base_price','description','status']);

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('menu_photos'), $filename);
        $data['photo_path'] = 'menu_photos/' . $filename;
    }

    Menu::create($data);

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
        'photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // ambil input biasa
    $data = $request->only(['name','category_id','base_price','description','status']);

    // kalau ganti foto
    if ($request->hasFile('photo')) {

        // hapus foto lama
        if ($menu->photo_path && file_exists(public_path($menu->photo_path))) {
            unlink(public_path($menu->photo_path));
        }

        // upload foto baru
        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('menu_photos'), $filename);

        // simpan path ke array update
        $data['photo_path'] = 'menu_photos/' . $filename;
    }

    // update semua
    $menu->update($data);

    return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui!');
}

public function destroy(Menu $menu)
{
    // Kalau mau sekalian hapus foto
    if ($menu->photo_path && \Storage::exists($menu->photo_path)) {
        \Storage::delete($menu->photo_path);
    }

    $menu->delete(); // Soft delete

    return redirect()->route('menus.index')
        ->with('success', 'Menu berhasil dihapus!');
}

}
