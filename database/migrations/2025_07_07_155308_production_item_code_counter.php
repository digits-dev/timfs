<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductionItemCodeCounter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    

    public function up()
    {
       

         DB::table('code_counters')->insert([
            [ 
                'type' => 'PRODUCTION ITEMS',
                'code_1' => '0',
                'code_2' => '0',
                'code_3' => '0',
                'code_4' => '0',
                'code_5' => '0',
                'code_6' => '0',
                'code_7' => '700000001',
                'code_8' => '800000001',
                'code_9' => '0',
                'status' => 'ACTIVE',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2025-07-07 13:02:09',
                'updated_at' => null,
                'deleted_at' => null,
            ], 
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       //  DB::table('code_counters')->whereIn('type', 'PRODUCTION ITEMS')->delete();
    }
}
