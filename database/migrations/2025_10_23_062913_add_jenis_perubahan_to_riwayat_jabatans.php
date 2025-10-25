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
        Schema::table('riwayat_jabatans', function (Blueprint $table) {
            if (!Schema::hasColumn('riwayat_jabatans', 'jenis_perubahan')) {
                $table->enum('jenis_perubahan', ['Promosi', 'Mutasi', 'Demosi', 'Penugasan', 'Lainnya'])
                    ->default('Lainnya')
                    ->after('tanggal_selesai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_jabatans', function (Blueprint $table) {
            //
        });
    }
};
