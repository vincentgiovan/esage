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
        Schema::create('return_item_images', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("return_item_id");
            $table->foreign("return_item_id")->references("id")->on("return_items")->onDelete('cascade');

            $table->string('return_image_path');

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
        Schema::dropIfExists('return_item_images');
    }
};
