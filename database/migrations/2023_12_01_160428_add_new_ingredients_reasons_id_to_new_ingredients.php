<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewIngredientsReasonsIdToNewIngredients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->string('image_filename')->length(255)->after('ttp')->nullable();
            $table->string('segmentations')->length(255)->nullable()->after('target_date');
            $table->integer('new_ingredient_reasons_id')->length(10)->unsigned()->nullable()->after('segmentations');
            $table->string('existing_ingredient')->nullable()->after('new_ingredient_reasons_id');
            $table->string('recommended_brand_one')->nullable()->after('existing_ingredient');
            $table->string('recommended_brand_two')->nullable()->after('recommended_brand_one');
            $table->string('recommended_brand_three')->nullable()->after('recommended_brand_two');
            $table->decimal('initial_qty_needed', 18, 2)->unsigned()->nullable()->after('recommended_brand_three');
            $table->integer('initial_qty_uoms_id')->length(10)->unsigned()->nullable()->after('initial_qty_needed');
            $table->decimal('forecast_qty_needed', 18, 2)->unsigned()->nullable()->after('initial_qty_uoms_id');
            $table->integer('forecast_qty_uoms_id')->length(10)->unsigned()->nullable()->after('forecast_qty_needed');
            $table->text('budget_range')->nullable()->after('forecast_qty_uoms_id');
            $table->text('reference_link')->nullable()->after('budget_range');
            $table->integer('new_ingredient_terms_id')->length(10)->unsigned()->nullable()->after('reference_link');
            $table->string('duration')->length(255)->nullable()->after('new_ingredient_terms_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->dropColumn('image_filename');
            $table->dropColumn('segmentations');
            $table->dropColumn('new_ingredient_reasons_id');
            $table->dropColumn('existing_ingredient');
            $table->dropColumn('recommended_brand_one');
            $table->dropColumn('recommended_brand_two');
            $table->dropColumn('recommended_brand_three');
            $table->dropColumn('initial_qty_needed');
            $table->dropColumn('initial_qty_uoms_id');
            $table->dropColumn('forecast_qty_needed');
            $table->dropColumn('forecast_qty_uoms_id');
            $table->dropColumn('budget_range');
            $table->dropColumn('reference_link');
            $table->dropColumn('new_ingredient_terms_id');
            $table->dropColumn('duration');
        });
    }
}
