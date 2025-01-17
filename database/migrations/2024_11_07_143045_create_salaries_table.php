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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("employee_id");
            $table->foreign("employee_id")->references("id")->on("employees")->onDelete('cascade');

            $table->date('start_period');
            $table->date('end_period');

            $table->unsignedBigInteger("total");

            $table->longText("keterangan")->nullable();

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
        Schema::dropIfExists('salaries');
    }
};
