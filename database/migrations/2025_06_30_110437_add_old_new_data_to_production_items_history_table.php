<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldNewDataToProductionItemsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('production_items_history', function (Blueprint $table) {
            $table->text('old_data')->nullable()->after('description');
            $table->text('new_data')->nullable()->after('old_data');
        });

          // Remove packaging_id column from production_items table
        Schema::table('production_items', function (Blueprint $table) {
            if (Schema::hasColumn('production_items', 'packaging_id')) {
                $table->dropColumn('packaging_id');
            }
        });
    }


    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_items_history', function (Blueprint $table) {
            $table->dropColumn(['old_data', 'new_data']);
        });
       
    }
}
