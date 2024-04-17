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
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->integer('currency_id')->nullable()->after('cost');
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
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->dropColumn('currency_id');
        });
    }
}
