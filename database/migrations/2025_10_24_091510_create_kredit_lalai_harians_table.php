<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kredit_lalai_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('wilayah')->nullable(); // null = global (satu per hari)
            $table->decimal('total_piutang', 16, 2)->default(0);
            $table->decimal('total_lalai', 16, 2)->default(0);
            $table->decimal('rasio_lalai', 8, 2)->default(0); // disimpan biar mudah chart
            $table->unsignedBigInteger('user_id');
            $table->string('keterangan')->nullable();
            $table->timestamps();

            // Unik per (tanggal, wilayah). Saat wilayah NULL, anggap entri global.
            $table->unique(['tanggal', 'wilayah']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kredit_lalai_harian');
    }
};
