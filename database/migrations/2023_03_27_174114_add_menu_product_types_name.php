<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuProductTypesName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            //
            $table->string('menu_product_types_name')->length(50)->nullable()->after('menu_product_types_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            //
            $table->dropColumn('menu_product_types_name');
        });
    }
}
