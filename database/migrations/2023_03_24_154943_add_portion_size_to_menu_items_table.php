<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPortionSizeToMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            DB::statement('alter table menu_items add column `portion_size` float null default "1" after uoms_id');
            DB::statement('alter table menu_items add column `ingredient_total_cost` float null after uoms_id');
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
            $table->dropColumn('portion_size');
            $table->dropColumn('ingredient_total_cost');
        });
    }
}
