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

            $table->string("product_name");
            $table->string("variant")->nullable();
            $table->string("product_code");
            $table->unsignedBigInteger("price");
            $table->unsignedFloat("discount")->default(0);

            $table->string("unit")->nullable();
            $table->unsignedInteger("stock");
            $table->string("status");
            $table->unsignedFloat("markup")->nullable();

            $table->string("is_returned")->default('no');

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
        Schema::dropIfExists('produk');
    }
};
