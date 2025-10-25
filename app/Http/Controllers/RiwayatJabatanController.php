<?php

namespace App\Http\Controllers;

use App\Models\RiwayatJabatan;
use App\Models\Pegawai;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class RiwayatJabatanController extends Controller
{
    /**
     * Tampilkan daftar riwayat jabatan pegawai.
     */
    public function index($pegawai_id)
    {
        $pegawai = Pegawai::findOrFail($pegawai_id);
        $riwayats = $pegawai->riwayatJabatans()->with('jabatan.bagian')->get();
        return view('riwayat.index', compact('pegawai', 'riwayats'));
    }

    /**
     * Tampilkan form tambah riwayat jabatan.
     */
    public function create($pegawai_id)
    {
        $pegawai = Pegawai::findOrFail($pegawai_id);
        $jabatans = Jabatan::with('bagian')->get();
        return view('riwayat.create', compact('pegawai', 'jabatans'));
    }

    /**
     * Simpan riwayat jabatan baru.
     */
    public function store(Request $request, $pegawai_id)
    {
        $request->validate([
            'jabatan_id' => 'required',
            'tanggal_mulai' => 'required|date',
        ]);

        $pegawai = Pegawai::findOrFail($pegawai_id);

        $riwayat = RiwayatJabatan::create([
            'pegawai_id' => $pegawai_id,
            'jabatan_id' => $request->jabatan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
        ]);

        $riwayat->load('jabatan.bagian');

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'riwayat' => $riwayat]);
        }

        return redirect()->back()->with('success', 'Riwayat jabatan ditambahkan.');
    }


    /**
     * Tampilkan form edit riwayat jabatan.
     */
    public function edit($pegawai_id, $id)
    {
        $pegawai = Pegawai::findOrFail($pegawai_id);
        $riwayat = RiwayatJabatan::findOrFail($id);
        $jabatans = Jabatan::with('bagian')->get();
        return view('riwayat.edit', compact('pegawai', 'riwayat', 'jabatans'));
    }
    /**
     * Perbarui data riwayat jabatan.
     */
    public function update(Request $request, $pegawai_id, $id)
    {
        $request->validate([
            'jabatan_id' => 'required|exists:jabatans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $riwayat = RiwayatJabatan::findOrFail($id);
        $pegawai = Pegawai::findOrFail($pegawai_id);

        $riwayat->update([
            'jabatan_id' => $request->jabatan_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'is_current' => empty($request->tanggal_selesai),
            'updated_by' => auth()->id(),
        ]);

        // Perbarui jabatan aktif pegawai
        if ($riwayat->is_current) {
            $pegawai->update(['jabatan_id' => $request->jabatan_id]);
        }

        return redirect()->route('riwayat.index', $pegawai_id)
            ->with('success', 'Data riwayat jabatan berhasil diperbarui.');
    }

    /**
     * Hapus riwayat jabatan.
     */
    public function destroy($pegawai_id, $id)
    {
        $riwayat = RiwayatJabatan::findOrFail($id);
        $riwayat->delete();

        return response()->json(['success' => true]);
    }
}
