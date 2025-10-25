<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BagianController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya user login dan punya izin yang boleh akses
        $this->middleware(['auth']);
        $this->middleware('permission:view bagian|manage bagian');
    }

    /**
     * Tampilkan daftar semua bagian.
     */
    public function index()
    {
        $bagians = Bagian::orderBy('nama_bagian')->paginate(10);
        return view('bagian.index', compact('bagians'));
    }

    /**
     * Tampilkan form untuk membuat bagian baru.
     */
    public function create()
    {
        $this->authorize('manage bagian');
        return view('bagian.create');
    }

    /**
     * Simpan data bagian baru ke database.
     */
    public function store(Request $request)
    {
        $this->authorize('manage bagian');

        $request->validate([
            'nama_bagian' => 'required|string|max:100|unique:bagians,nama_bagian',
        ]);

        Bagian::create([
            'nama_bagian' => $request->nama_bagian,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('bagian.index')->with('success', 'Bagian berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit untuk bagian tertentu.
     */
    public function edit(Bagian $bagian)
    {
        $this->authorize('manage bagian');
        return view('bagian.edit', compact('bagian'));
    }

    /**
     * Update data bagian di database.
     */
    public function update(Request $request, Bagian $bagian)
    {
        $this->authorize('manage bagian');

        $request->validate([
            'nama_bagian' => 'required|string|max:100|unique:bagians,nama_bagian,' . $bagian->id,
        ]);

        $bagian->update([
            'nama_bagian' => $request->nama_bagian,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('bagian.index')->with('success', 'Bagian berhasil diperbarui.');
    }

    /**
     * Hapus bagian beserta semua jabatan di dalamnya.
     */
    public function destroy(Bagian $bagian)
    {
        $this->authorize('manage bagian');

        $bagian->delete();

        return redirect()->route('bagian.index')->with('success', 'Bagian berhasil dihapus.');
    }
}
