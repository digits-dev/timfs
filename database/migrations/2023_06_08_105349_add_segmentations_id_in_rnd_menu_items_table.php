<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSegmentationsIdInRndMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rnd_menu_items', function (Blueprint $table) {
            $table->integer('segmentations_id')->length(10)->unsigned()->nullable()->after('rnd_menu_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rnd_menu_items', function (Blueprint $table) {
            $table->dropColumn('segmentations_id');
        });
    }
}
