<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_products', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("desription");
            $table->float("price");
            $table->string("image");
            $table->unsignedBigInteger("shipping_id");
            $table->timestamps();

            $table->foreign("shipping_id")->references("id")->on("shippings");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_products');
    }
}
