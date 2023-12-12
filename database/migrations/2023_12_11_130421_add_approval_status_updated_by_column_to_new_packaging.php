<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalStatusUpdatedByColumnToNewPackaging extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_packagings', function (Blueprint $table) {
            $table->smallInteger('approval_status_updated_by')->unsigned()->nullable()->after('is_taggable');
            $table->timestamp('approval_status_updated_at')->nullable()->after('approval_status_updated_by');
            $table->smallInteger('sourcing_status_updated_by')->unsigned()->nullable()->after('approval_status_updated_at');
            $table->timestamp('sourcing_status_updated_at')->nullable()->after('sourcing_status_updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_packagings', function (Blueprint $table) {
            $table->dropColumn('approval_status_updated_at');
            $table->dropColumn('approval_status_updated_by');
            $table->dropColumn('sourcing_status_updated_at');
            $table->dropColumn('sourcing_status_updated_by');
        });
    }
}
