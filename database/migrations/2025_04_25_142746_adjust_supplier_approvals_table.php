<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustSupplierApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplier_approvals', function (Blueprint $table) {
            $table->unsignedInteger('vendor_types_id')->nullable()->after('countries_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplier_approvals', function (Blueprint $table) {
            $table->dropColumn('vendor_types_id');
        });
    }
}
