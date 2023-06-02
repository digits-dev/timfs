<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBatchingIngredientsIdColumnToMenuIngredientsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            $table->integer('new_ingredients_id')->length(10)->unsigned()->after('item_masters_id')->nullable();
            $table->integer('batching_ingredients_id')->length(10)->unsigned()->after('menu_as_ingredient_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            $table->dropColumn('new_ingredients_id');
            $table->dropColumn('batching_ingredients_id');
        });
    }
}
