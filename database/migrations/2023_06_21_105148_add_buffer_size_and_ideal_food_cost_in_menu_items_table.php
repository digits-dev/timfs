<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBufferSizeAndIdealFoodCostInMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->decimal('ideal_food_cost', 18, 4)->default(30)->unsigned()->nullable()->after('status');
            $table->decimal('buffer', 18, 4)->default(6.5)->unsigned()->nullable()->after('status');
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
            $table->dropColumn('ideal_food_cost');
            $table->dropColumn('buffer');
        });
    }
}
