<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItemMastersFasAssetType extends Migration
{
    public function up()
    {
        Schema::table('item_masters_fas', function (Blueprint $table) {
            $table->string('asset_type')->after('color');
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->string('asset_type')->after('color');
        });
    }

    public function down()
    {
        Schema::table('item_masters_fas', function (Blueprint $table) {
            $table->dropColumn('asset_type');
        });

        Schema::table('item_masters_fas_approvals', function (Blueprint $table) {
            $table->dropColumn('asset_type');
        });
    }

}
