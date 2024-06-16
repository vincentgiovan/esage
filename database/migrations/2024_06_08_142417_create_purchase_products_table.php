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
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat("discount")->nullable();
            $table->unsignedInteger("quantity");
            $table->unsignedBigInteger("price");
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("purchase_id");

            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
            $table->foreign("purchase_id")->references("id")->on("products")->onDelete("cascade");

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
        Schema::dropIfExists('purchase_products');
    }
};
