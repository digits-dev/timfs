<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageFilenameColumnToItemMasters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->text('image_filename')->nullable()->after('purchase_description');
        });

        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->text('image_filename')->nullable()->after('purchase_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->dropColumn('image_filename');
        });

        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->dropColumn('image_filename');
        });
    }
}
