<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string("tracking")->unique();
            $table->text("description");
            $table->unsignedBigInteger("recipient_id");
            $table->unsignedBigInteger("box_id");
            $table->integer("pieces")->nullable();
            $table->float("length")->nullable();
            $table->float("width")->nullable();
            $table->float("height")->nullable();
            $table->float("weight")->nullable();
            $table->timestamps();

            $table->foreign("recipient_id")->references("id")->on("recipients");
            $table->foreign("box_id")->references("id")->on("boxes");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
}
