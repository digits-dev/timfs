<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ItemApprovalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['PENDING', 'APPROVED', 'REJECTED'];

        foreach ($statuses as $status) {
            DB::table('item_approval_statuses')->updateOrInsert(['status_description' => $status], [
                'status_description' => $status,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
