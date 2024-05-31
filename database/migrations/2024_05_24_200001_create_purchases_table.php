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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger("supplier_id");
            $table->string("register");
            $table->date("purchase_deadline");
            $table->longText("note")->nullable();
            $table->date("purchase_date");
            $table->unsignedBigInteger("product_id");
            $table->string("purchase_status");

            $table->foreign("supplier_id")->references("id")->on("partners");
            $table->foreign("product_id")->references("id")->on("products");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
};
