<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemLinesItemCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('production_item_lines', function (Blueprint $table) {
            $table->string('item_code', 15)->change();
        });
         Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->string('item_code', 15)->change();
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
            $table->integer('item_code')->change();
        });
        
        Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->integer('item_code')->change();
        });
    }
}
