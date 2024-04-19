<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSkuStatusesIdToItemMastersFas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_masters_fas', function (Blueprint $table) {
            $table->integer('sku_statuses_id')->nullable()->after('approval_status');
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->integer('sku_statuses_id')->nullable()->after('approval_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_masters_fas', function (Blueprint $table) {
            $table->dropColumn('sku_statuses_id');
        });
        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->dropColumn('sku_statuses_id');
        });
    }
}
