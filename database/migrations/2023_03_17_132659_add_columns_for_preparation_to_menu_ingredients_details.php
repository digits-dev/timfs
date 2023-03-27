<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForPreparationToMenuIngredientsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_ingredients_details', function (Blueprint $table) {
            DB::statement('alter table menu_ingredients_details add column `prep_qty` float null after qty');
            DB::statement('alter table menu_ingredients_details add column `menu_ingredients_preparations_id` int(10) null after uom_name');
            DB::statement('alter table menu_ingredients_details add column `yield` float null after menu_ingredients_preparations_id');
            DB::statement('alter table menu_ingredients_details add column `ttp` float null after yield');
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
            $table->dropColumn('prep_qty');
            $table->dropColumn('menu_ingredients_preparations_id');
            $table->dropColumn('yield');
            $table->dropColumn('ttp');
        });
    }
}
