<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained('jabatans')->onDelete('cascade');

            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            // Kolom tambahan penting:
            $table->enum('jenis_perubahan', ['Promosi', 'Mutasi', 'Demosi', 'Penugasan', 'Lainnya'])
                ->default('Lainnya')
                ->comment('Jenis perubahan jabatan pegawai');

            $table->text('keterangan')->nullable();

            // Menandai jabatan yang sedang aktif
            $table->boolean('is_current')->default(false);

            // Logging data user
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes(); // agar bisa undo delete riwayat jika salah
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_jabatans');
    }
};
