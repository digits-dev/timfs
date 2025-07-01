<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductionItemsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_items_history', function(Blueprint $table){
            $table->dropColumn(['old_data','new_data']);

            $table->string('key_old_value', 255)->nullable()->after('description');
            $table->string('description_old_value', 255)->nullable()->after('key_old_value');
            $table->string('key_new_value', 255)->nullable()->after('description_old_value');
            $table->string('description_new_value', 255)->nullable()->after('key_new_value'); 
            $table->integer('updated_by')->nullable()->after('description_new_value');

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
            $table->text('old_data')->nullable()->after('description');
            $table->text('new_data')->nullable()->after('old_data'); 
            $table->dropColumn([
                'key_old_value',
                'description_old_value',
                'key_new_value',
                'description_new_value', 
                'updated_by'
            ]);
        });
    }
}
