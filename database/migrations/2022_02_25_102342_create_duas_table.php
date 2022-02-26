<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shipping_guide_id");
            $table->foreign("shipping_guide_id")->references("id")->on("shipping_guides");
            $table->string("hawb");
            $table->string("esser");
            $table->string("client");
            $table->string("volante");
            $table->string("tc");
            $table->date("arrivalDate");
            $table->string("dua");
            $table->string("manifest");
            $table->string("awb");
            $table->integer("pieces");
            $table->double("weight", 8, 2);
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
        Schema::dropIfExists('duas');
    }
}
