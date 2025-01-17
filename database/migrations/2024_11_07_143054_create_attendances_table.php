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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->date('attendance_date');

            $table->unsignedBigInteger("employee_id");
            $table->foreign("employee_id")->references("id")->on("employees")->onDelete('cascade');

            $table->unsignedBigInteger("project_id");
            $table->foreign("project_id")->references("id")->on("projects")->onDelete('cascade');

            $table->time("jam_masuk");
            $table->time("jam_keluar")->nullable();

            $table->float("normal")->nullable();
            $table->float("jam_lembur")->nullable();
            $table->float("index_lembur_panjang")->nullable();
            $table->unsignedBigInteger("performa")->nullable();
            $table->float("remark")->nullable();

            $table->double('latitude_masuk', 15, 8)->nullable();
            $table->double('longitude_masuk', 15, 8)->nullable();
            $table->double('latitude_keluar', 15, 8)->nullable();
            $table->double('longitude_keluar', 15, 8)->nullable();

            $table->string('bukti_masuk')->nullable();
            $table->string('bukti_keluar')->nullable();

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
        Schema::dropIfExists('attendances');
    }
};
