<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prepay_cuts', function (Blueprint $table) {
            $table->id();

            $table->date('start_period');
            $table->date('end_period');

            $table->unsignedBigInteger('prepay_id');
            $table->foreign('prepay_id')->references('id')->on('prepays');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prepay_cuts');
    }
};
