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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("product_name");
            $table->string("unit")->nullable();
            $table->string("status");
            $table->string("variant")->nullable();
            $table->string("product_code");
            $table->unsignedBigInteger("price");
            $table->unsignedFloat("discount");
            $table->unsignedInteger("stock");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produk');
    }
};
