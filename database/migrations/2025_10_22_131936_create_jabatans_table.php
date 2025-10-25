<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();

            // Nama jabatan unik dalam satu bagian (tidak boleh dua jabatan sama di bagian sama)
            $table->string('nama_jabatan');
            $table->foreignId('bagian_id')->constrained('bagians')->onDelete('cascade');

            // Kolom tambahan untuk manajemen dan audit
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Tambahan constraint unik (optional tapi disarankan)
            $table->unique(['nama_jabatan', 'bagian_id'], 'unique_jabatan_per_bagian');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};
