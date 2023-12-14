<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileLinkColumnInItemMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_masters', function (Blueprint $table) {
            $table->mediumText('file_link')->nullable()->after('image_filename');
        });

        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->mediumText('file_link')->nullable()->after('image_filename');
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
            $table->dropColumn('file_link');
        });

        Schema::table('item_master_approvals', function (Blueprint $table) {
            $table->dropColumn('file_link');
        });
    }
}
