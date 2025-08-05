<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemLinesTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->string('production_item_line_type', 100)->nullable()->after('approval_status');
            $table->string('preparations', 100)->nullable()->after('production_item_line_type'); 
            $table->string('time_labor', 100)->nullable()->after('preparations'); 
        });
         Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->string('production_item_line_type', 100)->nullable()->after('approval_status');
            $table->string('preparations', 100)->nullable()->after('production_item_line_type'); 
            $table->string('time_labor', 100)->nullable()->after('preparations'); 
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
            $table->dropColumn(['production_item_line_type', 'preparations', 'time_labor']);
        });

        Schema::table('production_item_lines_approvals', function (Blueprint $table) {
            $table->dropColumn(['production_item_line_type', 'preparations', 'time_labor']);
        });
    }
}
