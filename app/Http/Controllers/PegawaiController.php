<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\Bagian;
use App\Models\RiwayatJabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['jabatan.bagian']);

        if ($request->filled('bagian_id')) {
            $query->whereHas('jabatan.bagian', fn($q) => $q->where('id', $request->bagian_id));
        }

        if ($request->filled('jabatan_id')) {
            $query->where('jabatan_id', $request->jabatan_id);
        }

        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('nama', 'like', "%{$request->search}%")
                ->orWhere('nip', 'like', "%{$request->search}%"));
        }

        $pegawais = $query->orderBy('nama')->paginate(10)->withQueryString();
        $bagians = Bagian::orderBy('nama_bagian')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('pegawai.index', compact('pegawais', 'bagians', 'jabatans'));
    }

    public function create()
    {
        $pegawai = new Pegawai();
        $jabatans = Jabatan::with('bagian')->get();
        return view('pegawai.create', compact('pegawai', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:pegawais',
            'nama' => 'required|string|max:100',
            'jabatan_id' => 'required|exists:jabatans,id',
            'tanggal_masuk' => 'required|date',
            'status_kerja' => 'required|in:Tetap,Kontrak,Magang',
            'email' => 'nullable|email|unique:pegawais,email',
            'foto' => 'nullable|image|max:2048'
        ]);

        $data = $request->only([
            'nip',
            'nama',
            'email',
            'no_hp',
            'alamat',
            'tanggal_masuk',
            'status_kerja',
            'jabatan_id'
        ]);
        $data['created_by'] = auth()->id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_pegawai', 'public');
        }

        $pegawai = Pegawai::create($data);

        // Buat riwayat jabatan awal
        RiwayatJabatan::create([
            'pegawai_id' => $pegawai->id,
            'jabatan_id' => $pegawai->jabatan_id,
            'tanggal_mulai' => $pegawai->tanggal_masuk,
            'is_current' => true,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function profil(Pegawai $pegawai)
    {
        $pegawai->load('jabatan.bagian', 'riwayatJabatans.jabatan.bagian');
        $riwayats = $pegawai->riwayatJabatans->sortByDesc('tanggal_mulai');
        $jabatans = Jabatan::with('bagian')->get();

        return view('pegawai.profil', compact('pegawai', 'riwayats', 'jabatans'));
    }

    public function storeRiwayat(Request $request, Pegawai $pegawai)
    {
        $data = $request->validate([
            'jabatan_id' => 'required|exists:jabatans,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Tutup jabatan aktif sebelumnya
        $jabatanLama = $pegawai->riwayatJabatans()->where('is_current', true)->first();
        if ($jabatanLama) {
            $jabatanLama->update([
                'tanggal_selesai' => date('Y-m-d', strtotime($data['tanggal_mulai'] . ' -1 day')),
                'is_current' => false,
            ]);
        }

        // Tambahkan jabatan baru
        $data['pegawai_id'] = $pegawai->id;
        $data['is_current'] = true;
        $data['created_by'] = auth()->id();

        $riwayat = \App\Models\RiwayatJabatan::create($data)->load('jabatan.bagian');

        // Update jabatan aktif pegawai
        $pegawai->update(['jabatan_id' => $data['jabatan_id']]);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat jabatan berhasil ditambahkan.',
            'riwayat' => $riwayat,
        ]);
    }


    public function destroyRiwayat(Pegawai $pegawai, \App\Models\RiwayatJabatan $riwayat)
    {
        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat jabatan berhasil dihapus.',
        ]);
    }

    public function edit(Pegawai $pegawai)
    {
        // Ambil semua jabatan lengkap dengan bagian-nya
        $jabatans = \App\Models\Jabatan::with('bagian')->get();

        // Ambil juga semua riwayat jabatan pegawai ini (jika ingin tampilkan di edit)
        $pegawai->load('jabatan.bagian', 'riwayatJabatans.jabatan.bagian');

        return view('pegawai.edit', compact('pegawai', 'jabatans'));
    }




    public function restore($id)
    {
        $pegawai = Pegawai::withTrashed()->findOrFail($id);
        $pegawai->restore();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dipulihkan.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
    }
}
