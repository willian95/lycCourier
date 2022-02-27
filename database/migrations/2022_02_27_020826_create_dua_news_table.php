<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuaNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dua_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shipping_guide_id");
            $table->foreign("shipping_guide_id")->references("id")->on("shipping_guides");
            $table->string("hawb")->nullable();
            $table->string("esser")->nullable();
            $table->string("client")->nullable();
            $table->string("volante")->nullable();
            $table->string("tc")->nullable();
            $table->date("arrivalDate")->nullable();
            $table->string("dua")->nullable();
            $table->string("manifest")->nullable();
            $table->string("awb")->nullable();
            $table->integer("pieces")->nullable();
            $table->double("weight", 8, 2)->nullable();
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
        Schema::dropIfExists('dua_news');
    }
}
