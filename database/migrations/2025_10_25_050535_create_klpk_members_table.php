<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlpkMembersTable extends Migration
{
    public function up()
    {
        Schema::create('klpk_members', function (Blueprint $table) {
            $table->id('klpk_id'); // AUTO INCREMENT KLPK ID
            $table->string('cif_number', 30);   // CIF unik
            $table->string('full_name');
            $table->string('id_number', 50)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->date('exit_date');          // tanggal keluar dari CU
            $table->string('loan_reference', 50)->nullable(); // nomor pinjaman sebelumnya
            $table->bigInteger('principal_start'); // sisa pokok saat masuk KLPK
            $table->bigInteger('principal_remaining'); // sisa pokok berjalan
            $table->string('officer_in_charge', 100)->nullable();
            $table->string('risk_level', 20)->nullable();
            $table->text('collateral_info')->nullable();
            $table->string('status_penagihan', 50)->nullable()
                ->default('Aktif');
            $table->text('first_notes')->nullable();
            $table->timestamps();

            $table->index('cif_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('klpk_members');
    }
}
