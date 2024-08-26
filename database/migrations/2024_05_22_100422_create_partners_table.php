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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("role");
            $table->string("partner_name");
            $table->string("remark")->nullable();
            $table->longText("address")->nullable();
            $table->longText("contact")->nullable();
            $table->string("phone")->nullable();
            $table->string("fax")->nullable();
            $table->string("email")->nullable();
            $table->string("tempo")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner');
    }
};
