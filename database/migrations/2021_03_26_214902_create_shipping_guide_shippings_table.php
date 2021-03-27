<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingGuideShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_guide_shippings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shipping_guide_id");
            $table->unsignedBigInteger("shipping_id");
            $table->foreign("shipping_guide_id")->references("id")->on("shipping_guides");
            $table->foreign("shipping_id")->references("id")->on("shippings");
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
        Schema::dropIfExists('shipping_guide_shippings');
    }
}
