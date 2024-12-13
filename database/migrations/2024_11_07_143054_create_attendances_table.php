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

            $table->float("normal");
            $table->float("jam_lembur");
            $table->float("index_lembur_panjang");
            $table->float("index_performa");
            $table->float("remark")->nullable();

            $table->double('latitude', 15, 8)->nullable();
            $table->double('longitude', 15, 8)->nullable();

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
