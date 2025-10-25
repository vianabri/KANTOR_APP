<?php

namespace App\Http\Controllers;

use App\Models\Atk;
use Illuminate\Http\Request;

class AtkController extends Controller
{
    public function index()
    {
        $items = Atk::latest()->paginate(10);
        return view('atk.index', compact('items'));
    }

    public function create()
    {
        return view('atk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'nullable|string|max:50',
            'keterangan'  => 'nullable|string',
        ]);

        Atk::create($request->only('nama_barang', 'satuan', 'keterangan'));

        return redirect()->route('atk.index')->with('success', 'Barang ATK berhasil ditambahkan!');
    }

    public function edit(Atk $atk)
    {
        return view('atk.edit', compact('atk'));
    }

    public function update(Request $request, Atk $atk)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'satuan'      => 'nullable|string|max:50',
            'keterangan'  => 'nullable|string',
        ]);

        $atk->update($request->only('nama_barang', 'satuan', 'keterangan'));

        return redirect()->route('atk.index')->with('success', 'Barang ATK berhasil diperbarui!');
    }

    public function destroy(Atk $atk)
    {
        $atk->delete();
        return back()->with('success', 'Barang ATK berhasil dihapus!');
    }
}
