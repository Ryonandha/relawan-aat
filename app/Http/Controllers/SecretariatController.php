<?php

namespace App\Http\Controllers;

use App\Models\Secretariat;
use Illuminate\Http\Request;

class SecretariatController extends Controller
{
    public function index()
    {
        $secretariats = Secretariat::all();
        return view('admin.secretariats.index', compact('secretariats'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:secretariats']);
        Secretariat::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Regional baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Secretariat::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Regional berhasil dihapus!');
    }
}