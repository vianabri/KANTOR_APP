<?php

namespace App\Http\Controllers;

use App\Models\Atk;
use App\Models\AtkKeluar;
use Illuminate\Http\Request;

class AtkKeluarController extends Controller
{
    public function index()
    {
        $items = AtkKeluar::with('atk')->latest()->paginate(10);
        return view('atk_keluar.index', compact('items'));
    }

    public function create()
    {
        $atk = Atk::orderBy('nama_barang')->get();
        return view('atk_keluar.create', compact('atk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'atk_id' => 'required|exists:atk,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'penerima' => 'nullable|string|max:255',
            'keperluan' => 'nullable|string'
        ]);

        $atk = Atk::find($request->atk_id);

        // Validasi stok tidak boleh minus
        if ($atk->stok < $request->jumlah_keluar) {
            return back()->with('error', 'Stok ATK tidak mencukupi!')
                ->withInput();
        }

        AtkKeluar::create([
            'atk_id' => $request->atk_id,
            'jumlah_keluar' => $request->jumlah_keluar,
            'tanggal_keluar' => $request->tanggal_keluar,
            'penerima' => $request->penerima,
            'keperluan' => $request->keperluan,
        ]);

        // Kurangi stok di master ATK
        $atk->decrement('stok', $request->jumlah_keluar);

        return redirect()->route('atk-keluar.index')
            ->with('success', 'Data pemakaian ATK berhasil ditambahkan!');
    }

    public function destroy(AtkKeluar $atkKeluar)
    {
        // Jika dihapus, stok harus dikembalikan
        $atk = Atk::find($atkKeluar->atk_id);
        $atk->increment('stok', $atkKeluar->jumlah_keluar);

        $atkKeluar->delete();

        return back()->with('success', 'Riwayat ATK keluar berhasil dihapus!');
    }
}
