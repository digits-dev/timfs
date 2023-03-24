<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackagingSizeColumnInMenuIngredientsDetailsTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details_temp', function (Blueprint $table) {
            DB::statement('alter table menu_ingredients_details_temp add column `packaging_size` float null after status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_ingredients_details_temp', function (Blueprint $table) {
            $table->dropColumn('packaging_size');
        });
    }
}
