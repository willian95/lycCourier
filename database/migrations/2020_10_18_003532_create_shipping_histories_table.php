<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shipping_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("shipping_status_id");
            $table->timestamps();

            $table->foreign("shipping_id")->references("id")->on("shippings");
            $table->foreign("user_id")->references("id")->on("users");
            $table->foreign("shipping_status_id")->references("id")->on("shipping_statuses");



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_histories');
    }
}
