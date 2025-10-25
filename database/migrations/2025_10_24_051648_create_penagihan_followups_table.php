<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penagihan_followup', function (Blueprint $table) {
            $table->id();

            // Relasi ke penagihan awal
            $table->foreignId('penagihan_id')->constrained('penagihan_lapangan')->cascadeOnDelete();

            // Follow-up dilakukan oleh siapa
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('hasil', ['BAYAR', 'JANJI', 'GAGAL']);

            $table->unsignedBigInteger('nominal_dibayar')->nullable();
            $table->date('tanggal_janji')->nullable();
            $table->string('kendala')->nullable();

            $table->text('catatan')->nullable();

            $table->timestamp('follow_up_date')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penagihan_followup');
    }
};
