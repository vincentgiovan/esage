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

            $table->unsignedBigInteger("partner_id");
            $table->foreign("partner_id")->references("id")->on("partners")->onDelete('cascade');

            $table->string("register");
            $table->date("purchase_deadline");
            $table->longText("note")->nullable();
            $table->date("purchase_date");
            $table->string("purchase_status");

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
        Schema::dropIfExists('pembelian');
    }
};
