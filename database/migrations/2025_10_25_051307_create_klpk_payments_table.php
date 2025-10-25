<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKlpkPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('klpk_payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('klpk_id');
            $table->date('payment_date');
            $table->bigInteger('payment_amount');
            $table->string('payment_method', 50)->nullable();
            $table->string('officer_in_charge', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('klpk_id')->references('klpk_id')->on('klpk_members')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('klpk_payments');
    }
}
