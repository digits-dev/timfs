<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ItemSourcingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['OPEN', 'CLOSED', 'CANCELLED', 'ON HOLD'];

        foreach ($statuses as $status) {
            DB::table('item_sourcing_statuses')->updateOrInsert(['status_description' => $status], [
                'status_description' => $status,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
