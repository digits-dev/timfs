<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSegmentationsIdInBatchingIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batching_ingredients', function (Blueprint $table) {
            $table->integer('segmentations_id')->nullable()->length(10)->unsigned()->after('uoms_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batching_ingredients', function (Blueprint $table) {
            $table->dropColumn('segmentations_id');
        });
    }
}
