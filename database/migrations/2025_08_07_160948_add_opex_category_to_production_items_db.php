<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOpexCategoryToProductionItemsDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_items', function (Blueprint $table) {  
            $table->integer('opex_category')->nullable()->after('ingredient_cost');  
        });
        Schema::table('production_items_approvals', function (Blueprint $table) {  
            $table->integer('opex_category')->nullable()->after('ingredient_cost');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_items', function (Blueprint $table) {
            $table->dropColumn('opex_category');
        });
        Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->dropColumn('opex_category');
        });
    }
}
