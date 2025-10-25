<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penagihan_lapangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('cif');                // CIF wajib
            $table->string('nama_anggota');       // nama manual
            $table->string('wilayah');            // wilayah manual

            $table->date('tanggal_kunjungan');

            $table->unsignedBigInteger('nominal_ditagih'); // gunakan BIG untuk aman
            $table->unsignedBigInteger('nominal_dibayar')->default(0);

            // BAYAR | JANJI | GAGAL
            $table->string('status', 10);
            $table->date('tanggal_janji')->nullable();   // wajib jika status = JANJI
            $table->string('kendala')->nullable();       // wajib jika status = GAGAL (atau tidak bayar)
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'tanggal_kunjungan']);
            $table->index(['cif']);
            $table->index(['wilayah']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penagihan_lapangan');
    }
};
