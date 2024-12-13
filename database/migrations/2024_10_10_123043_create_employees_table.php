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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string("nama");
            $table->string("NIK")->nullable();
            $table->string("foto_ktp")->nullable();
            $table->string("kalkulasi_gaji")->default("off");
            $table->string("jabatan")->nullable();
            $table->string("keahlian")->nullable();
            $table->unsignedBigInteger("pokok")->nullable();
            $table->unsignedBigInteger("lembur")->nullable();
            $table->unsignedBigInteger("lembur_panjang")->nullable();
            $table->unsignedBigInteger("performa")->nullable();
            $table->date("masuk")->nullable();
            $table->date("keluar")->nullable();
            $table->string("payroll")->default("off");
            $table->unsignedBigInteger("kasbon")->nullable();
            $table->longText("keterangan")->nullable();
            $table->string('status')->default('active');

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
        Schema::dropIfExists('employees');
    }
};
