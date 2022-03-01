<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnPiecesFromDuaNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dua_news', function (Blueprint $table) {
            $table->dropColumn("pieces");
            $table->dropColumn("weight");
            $table->dropColumn("arrivalDate");
            $table->dropForeign(["shipping_guide_id"]);
            $table->dropColumn("shipping_guide_id");
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
