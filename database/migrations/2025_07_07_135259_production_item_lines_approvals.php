<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemLinesApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('production_item_lines_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('production_item_id')->nullable();
            $table->integer('item_code')->nullable();
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 3);
            $table->decimal('landed_cost', 10, 3);
            $table->decimal('yield', 10, 2)->nullable(); 
            $table->string('packaging_id', 255)->nullable();
            $table->boolean('is_alternative')->default(false);
            $table->integer('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });


        Schema::table('production_item_lines', function (Blueprint $table) {
           $table->integer('approved_by')->nullable()->after('is_alternative');
           $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('production_item_lines_approvals');

         
        Schema::table('production_item_lines', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approved_at']);
        });
    }
}
