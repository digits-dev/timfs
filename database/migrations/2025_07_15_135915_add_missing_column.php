<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumn extends Migration
{
     public function up()
    {
        Schema::table('production_items', function (Blueprint $table) {
            $table->decimal('labor_cost_per_minute', 18, 5)->nullable()->after('labor_cost');
            $table->decimal('total_minutes_per_pack', 18, 5)->nullable()->after('labor_cost_per_minute');
            $table->decimal('labor_cost_val', 18, 5)->nullable()->after('total_minutes_per_pack');
            $table->string('transfer_price_category', 100)->nullable()->after('utilities');
        });

        Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->decimal('labor_cost_per_minute', 18, 5)->nullable()->after('labor_cost');
            $table->decimal('total_minutes_per_pack', 18, 5)->nullable()->after('labor_cost_per_minute');
            $table->decimal('labor_cost_val', 18, 5)->nullable()->after('total_minutes_per_pack');
            $table->string('utilities', 100)->nullable()->after('gas_cost');
            $table->string('transfer_price_category', 100)->nullable()->after('utilities');
        });
    }

    public function down()
    {
        Schema::table('production_items', function (Blueprint $table) {
            $table->dropColumn([
                'labor_cost_per_minute',
                'total_minutes_per_pack',
                'labor_cost_val',
                'transfer_price_category',
            ]);
        });

        Schema::table('production_items_approvals', function (Blueprint $table) {
            $table->dropColumn([
                'labor_cost_per_minute',
                'total_minutes_per_pack',
                'utilities',
                'labor_cost_val',
                'transfer_price_category',
            ]);
        });
    }
}
