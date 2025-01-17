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
            Schema::create('delivery_orders', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->date("delivery_date");
                $table->string("delivery_status");
                $table->unsignedBigInteger("project_id");
                $table->string("register");
                $table->longText("note")->nullable();
                $table->foreign("project_id")->references("id")->on("projects")->onDelete('cascade');

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
        Schema::dropIfExists('delivery_order');
    }
};
