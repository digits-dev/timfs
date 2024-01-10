<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemSourcingStatusesIdColumnToNewIngredientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_ingredients', function (Blueprint $table) {
            $table->smallInteger('item_sourcing_statuses_id')->nullable()->unsigned()->after('id');
            $table->smallInteger('item_approval_statuses_id')->nullable()->unsigned()->after('id');
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
            $table->dropColumn('item_sourcing_statuses_id');
            $table->dropColumn('item_approval_statuses_id');
        });
    }
}
