<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemApprovalStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_items', function (Blueprint $table) {
           $table->integer('approval_status')->nullable()->after('approved_by'); 
        });
          Schema::table('production_items_approvals', function (Blueprint $table) {
           $table->integer('approval_status')->nullable()->after('approved_by'); 
        });

        Schema::table('production_item_lines', function (Blueprint $table) {
           $table->integer('approval_status')->nullable()->after('approved_by'); 
        });
          Schema::table('production_item_lines_approvals', function (Blueprint $table) {
           $table->integer('approval_status')->nullable()->after('approved_by'); 
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
            $table->dropColumn(['approval_status']);
        });

         Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->dropColumn(['approval_status']);
        });

           Schema::table('production_item_lines', function (Blueprint $table) {
            $table->dropColumn(['approval_status']);
        });

         Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->dropColumn(['approval_status']);
        });
    }
}
