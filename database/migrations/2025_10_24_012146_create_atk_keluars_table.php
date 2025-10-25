<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('atk_keluar', function (Blueprint $table) {
            $table->id();

            // Barang yang keluar (relasi ke ATK)
            $table->foreignId('atk_id')
                ->constrained('atk')
                ->onDelete('cascade');

            // Informasi transaksi keluar
            $table->integer('jumlah_keluar');    // stok keluar
            $table->string('penerima')->nullable();   // nama pegawai / bagian
            $table->date('tanggal_keluar');     // tanggal pemakaian
            $table->text('keperluan')->nullable();    // untuk apa barang digunakan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atk_keluar');
    }
};
