<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlpkFollowupsTable extends Migration
{
    public function up()
    {
        Schema::create('klpk_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('klpk_id');
            $table->string('followup_type'); // Telp, WA, Kunjungan, dll
            $table->date('followup_date');
            $table->text('notes')->nullable();
            $table->string('officer')->nullable();
            $table->string('followup_status')->nullable(); // Janji Bayar, Tidak bertemu, dll
            $table->date('next_followup')->nullable();
            $table->timestamps();

            $table->foreign('klpk_id')
                ->references('klpk_id')->on('klpk_members')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('klpk_followups');
    }
}
