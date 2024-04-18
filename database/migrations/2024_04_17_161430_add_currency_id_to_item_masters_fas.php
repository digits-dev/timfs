<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyIdToItemMastersFas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_masters_fas', function (Blueprint $table) {
            $table->integer('currency_id')->nullable()->after('cost');
            $table->string('vendor2_id')->length(191)->nullable()->after('vendor_id');
            $table->string('vendor3_id')->length(191)->nullable()->after('vendor2_id');
            $table->string('vendor4_id')->length(191)->nullable()->after('vendor3_id');
            $table->string('vendor5_id')->length(191)->nullable()->after('vendor4_id');
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->integer('currency_id')->nullable()->after('cost');
            $table->string('vendor2_id')->length(191)->nullable()->after('vendor_id');
            $table->string('vendor3_id')->length(191)->nullable()->after('vendor2_id');
            $table->string('vendor4_id')->length(191)->nullable()->after('vendor3_id');
            $table->string('vendor5_id')->length(191)->nullable()->after('vendor4_id');
        });

        Schema::table('item_masters_fas', function(Blueprint $table) {
            $table->renameColumn('vendor_id', 'vendor1_id');
        });

        Schema::table('item_masters_fas_approvals', function(Blueprint $table) {
            $table->renameColumn('vendor_id', 'vendor1_id');
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
            $table->dropColumn('currency_id');
            $table->dropColumn('vendor2_id');
            $table->dropColumn('vendor3_id');
            $table->dropColumn('vendor4_id');
            $table->dropColumn('vendor5_id');
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->dropColumn('currency_id');
            $table->dropColumn('vendor2_id');
            $table->dropColumn('vendor3_id');
            $table->dropColumn('vendor4_id');
            $table->dropColumn('vendor5_id');
        });

        Schema::table('item_masters_fas', function(Blueprint $table) {
            $table->renameColumn('vendor1_id', 'vendor_id');
        });

        Schema::table('item_masters_fas_approvals', function(Blueprint $table) {
            $table->renameColumn('vendor1_id', 'vendor_id');
        });
    }
}
