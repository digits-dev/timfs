<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYieldAndIngredientQtyToProductionItemLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->decimal('yield', 10, 2)->nullable()->after('landed_cost'); 
             $table->string('packaging_id', 255)->nullable()->after('yield');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_item_lines', function (Blueprint $table) {
             $table->dropColumn(['yield', 'packaging_id']);
        });
    }
}
