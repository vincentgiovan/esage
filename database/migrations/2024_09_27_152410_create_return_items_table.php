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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("project_id");
            $table->foreign("project_id")->references("id")->on("projects")->onDelete('cascade');

            $table->date('return_date');
            $table->string("PIC");
            $table->string('driver');
            $table->string("status");

            $table->timestamps();

            $table->unsignedInteger('archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('return_items');
    }
};
