<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackagingTypesIdToNewPackagings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_packagings', function (Blueprint $table) {
            $table->integer('forecast_qty_uoms_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->decimal('forecast_qty_needed', 18, 2)->unsigned()->nullable()->after('target_date');
            $table->integer('initial_qty_uoms_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->decimal('initial_qty_needed', 18, 2)->unsigned()->nullable()->after('target_date');
            $table->text('reference_link')->nullable()->after('target_date');
            $table->text('budget_range')->nullable()->after('target_date');
            $table->decimal('size', 18, 2)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_design_types_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_paper_types_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_material_types_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_beverage_types_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_uniform_types_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_uses_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('sticker_types_id')->length(10)->unsigned()->nullable()->after('target_date');
            $table->integer('packaging_types_id')->length(10)->unsigned()->nullable()->after('target_date');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_packagings', function (Blueprint $table) {
            $table->dropColumn('packaging_types_id');
            $table->dropColumn('sticker_types_id');
            $table->dropColumn('packaging_uses_id');
            $table->dropColumn('packaging_uniform_types_id');
            $table->dropColumn('packaging_beverage_types_id');
            $table->dropColumn('packaging_material_types_id');
            $table->dropColumn('packaging_paper_types_id');
            $table->dropColumn('packaging_design_types_id');
            $table->dropColumn('size');
            $table->dropColumn('budget_range');
            $table->dropColumn('reference_link');
            $table->dropColumn('initial_qty_needed');
            $table->dropColumn('initial_qty_uoms_id');
            $table->dropColumn('forecast_qty_needed');
            $table->dropColumn('forecast_qty_uoms_id');

        });
    }
}
