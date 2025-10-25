<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('atk', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->string('satuan')->nullable(); // pcs, box, rim
            $table->integer('stok')->default(0); // stok akhir
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('atk');
    }
};
