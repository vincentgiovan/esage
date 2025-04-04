<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//testus4
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_order_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("delivery_order_id");
            $table->unsignedInteger("quantity");
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
            $table->foreign("delivery_order_id")->references("id")->on("delivery_orders")->onDelete("cascade");

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
        Schema::dropIfExists('delivery_order_products');
    }
};
