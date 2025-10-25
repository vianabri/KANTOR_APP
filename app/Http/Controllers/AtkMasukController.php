<?php

namespace App\Http\Controllers;

use App\Models\Atk;
use App\Models\AtkMasuk;
use Illuminate\Http\Request;

class AtkMasukController extends Controller
{
    public function index()
    {
        $items = AtkMasuk::with('atk')->latest()->paginate(10);
        return view('atk_masuk.index', compact('items'));
    }

    public function create()
    {
        $atk = Atk::orderBy('nama_barang')->get();
        return view('atk_masuk.create', compact('atk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'atk_id' => 'required|exists:atk,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date'
        ]);

        $totalHarga = $request->jumlah_masuk * $request->harga_satuan;

        AtkMasuk::create([
            'atk_id' => $request->atk_id,
            'jumlah_masuk' => $request->jumlah_masuk,
            'harga_satuan' => $request->harga_satuan,
            'total_harga' => $totalHarga,
            'tanggal_masuk' => $request->tanggal_masuk,
            'supplier' => $request->supplier,
        ]);

        // Update stok di tabel ATK
        $atk = Atk::find($request->atk_id);
        $atk->increment('stok', $request->jumlah_masuk);

        return redirect()->route('atk-masuk.index')
            ->with('success', 'Data ATK masuk berhasil ditambahkan!');
    }

    public function destroy(AtkMasuk $atkMasuk)
    {
        // Kembalikan stok karena data pembelian dihapus
        $atk = Atk::find($atkMasuk->atk_id);
        $atk->decrement('stok', $atkMasuk->jumlah_masuk);

        $atkMasuk->delete();

        return back()->with('success', 'Riwayat barang masuk berhasil dihapus!');
    }
}
