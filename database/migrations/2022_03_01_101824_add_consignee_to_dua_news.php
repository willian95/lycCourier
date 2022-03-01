<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsigneeToDuaNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dua_news', function (Blueprint $table) {
            $table->string("consignee")->nullable();
            $table->string("description")->nullable();
            $table->string("address")->nullable();
            $table->string("category")->nullable();
            $table->string("document")->nullable();
            $table->string("value")->nullable();
            $table->text("warehouse")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dua_news', function (Blueprint $table) {
            //
        });
    }
}
