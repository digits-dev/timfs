<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSegmentationsIdColumnInMenuSegmentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_segmentations', function (Blueprint $table) {
            $table->integer('segmentations_id')->length(10)->unsigned()->nullable()->after('menu_segment_column_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_segmentations', function (Blueprint $table) {
            $table->dropColumn('segmentations_id');
        });
    }
}
