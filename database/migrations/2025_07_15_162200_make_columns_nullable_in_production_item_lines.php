<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInProductionItemLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->dropColumn(['is_alternative']);
        });
          Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->dropColumn(['is_alternative']);
        });

        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->integer('production_item_id')->nullable()->change();
            $table->string('item_code', 15)->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->decimal('quantity', 10, 3)->nullable()->change();
            $table->decimal('landed_cost', 10, 3)->nullable()->change();
            $table->decimal('yield', 10, 2)->nullable()->change();
            $table->string('packaging_id', 255)->nullable()->change(); 
            $table->integer('approved_by')->nullable()->change();
            $table->integer('production_item_line_id')->nullable()->change();
            $table->integer('approval_status')->nullable()->change();
            $table->string('production_item_line_type', 100)->nullable()->change();
            $table->string('preparations', 100)->nullable()->change();
            $table->string('time_labor', 100)->nullable()->change(); 
        });

         Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->integer('production_item_id')->nullable()->change();
            $table->string('item_code', 15)->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->decimal('quantity', 10, 3)->nullable()->change();
            $table->decimal('landed_cost', 10, 3)->nullable()->change();
            $table->decimal('yield', 10, 2)->nullable()->change();
            $table->string('packaging_id', 255)->nullable()->change(); 
            $table->integer('approved_by')->nullable()->change();
            $table->integer('production_item_line_id')->nullable()->change();
            $table->integer('approval_status')->nullable()->change();
            $table->string('production_item_line_type', 100)->nullable()->change();
            $table->string('preparations', 100)->nullable()->change();
            $table->string('time_labor', 100)->nullable()->change(); 
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
            // Add back the 'is_alternative' column (you need to specify its type & default)
            $table->tinyInteger('is_alternative')->default(0)->after('packaging_id');
            
            // Revert the nullable changes by making columns NOT nullable (adjust as needed)
            $table->integer('production_item_id')->nullable(false)->change();
            $table->string('item_code', 15)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->decimal('quantity', 10, 3)->nullable(false)->change();
            $table->decimal('landed_cost', 10, 3)->nullable(false)->change();
            $table->decimal('yield', 10, 2)->nullable(false)->change();
            $table->string('packaging_id', 255)->nullable(false)->change();
            $table->integer('approved_by')->nullable(false)->change();
            $table->integer('production_item_line_id')->nullable(false)->change();
            $table->integer('approval_status')->nullable(false)->change();
            $table->string('production_item_line_type', 100)->nullable(false)->change();
            $table->string('preparations', 100)->nullable(false)->change();
            $table->string('time_labor', 100)->nullable(false)->change();
        });

        Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            // Add back the 'is_alternative' column
            $table->tinyInteger('is_alternative')->default(0)->after('packaging_id');
            
            // Revert the nullable changes by making columns NOT nullable (adjust as needed)
            $table->integer('production_item_id')->nullable(false)->change();
            $table->string('item_code', 15)->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->decimal('quantity', 10, 3)->nullable(false)->change();
            $table->decimal('landed_cost', 10, 3)->nullable(false)->change();
            $table->decimal('yield', 10, 2)->nullable(false)->change();
            $table->string('packaging_id', 255)->nullable(false)->change();
            $table->integer('approved_by')->nullable(false)->change();
            $table->integer('production_item_line_id')->nullable(false)->change();
            $table->integer('approval_status')->nullable(false)->change();
            $table->string('production_item_line_type', 100)->nullable(false)->change();
            $table->string('preparations', 100)->nullable(false)->change();
            $table->string('time_labor', 100)->nullable(false)->change();
        });
    }
}
