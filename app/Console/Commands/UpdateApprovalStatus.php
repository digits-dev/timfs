<?php

namespace App\Console\Commands;

use App\MenuItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateApprovalStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:menu-approval-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking and Updating of Approval Status in Menu Items';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $hasEmptyApproval = MenuItem::whereNull('action_type')
            ->whereNull('approval_status')
            ->whereNotNull('tasteless_menu_code')
            ->pluck('id');

        if ($hasEmptyApproval->isNotEmpty()) {
            DB::statement("
                UPDATE menu_items 
                SET action_type = 'Create',
                    approval_status = '1',
                    approved_at = menu_items.created_at 
                WHERE action_type IS NULL 
                AND approval_status IS NULL
                AND tasteless_menu_code IS NOT NULL;
            ");

            Log::info('Menu items with empty approval were updated.', [
                'updated_ids' => $hasEmptyApproval->toArray(),
                'record_count' => $hasEmptyApproval->count(),
            ]);

        } else {
            Log::info('No menu items found with empty approval to update.');
        }
    }
}
