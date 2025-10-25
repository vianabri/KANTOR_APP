<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:view jabatan|manage jabatan');
    }

    /**
     * Tampilkan semua jabatan.
     */
    public function index()
    {
        $jabatans = Jabatan::with('bagian')->orderBy('nama_jabatan')->paginate(10);
        return view('jabatan.index', compact('jabatans'));
    }

    /**
     * Form tambah jabatan baru.
     */
    public function create()
    {
        $this->authorize('manage jabatan');

        $bagians = Bagian::orderBy('nama_bagian')->get();
        return view('jabatan.create', compact('bagians'));
    }

    /**
     * Simpan jabatan baru ke database.
     */
    public function store(Request $request)
    {
        $this->authorize('manage jabatan');

        $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'bagian_id' => 'required|exists:bagians,id',
        ]);

        Jabatan::create([
            'nama_jabatan' => $request->nama_jabatan,
            'bagian_id' => $request->bagian_id,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Form edit jabatan.
     */
    public function edit(Jabatan $jabatan)
    {
        $this->authorize('manage jabatan');
        $bagians = Bagian::orderBy('nama_bagian')->get();

        return view('jabatan.edit', compact('jabatan', 'bagians'));
    }

    /**
     * Update jabatan.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $this->authorize('manage jabatan');

        $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'bagian_id' => 'required|exists:bagians,id',
        ]);

        $jabatan->update([
            'nama_jabatan' => $request->nama_jabatan,
            'bagian_id' => $request->bagian_id,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Hapus jabatan.
     */
    public function destroy(Jabatan $jabatan)
    {
        $this->authorize('manage jabatan');
        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
