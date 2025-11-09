<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        return view('select-table');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string',
            'device_token' => 'required|string',
        ]);

        session([
            'table_number' => $request->table_number,
            'device_token' => $request->device_token,
        ]);

        return redirect()->route('menu.index');
    }
}
