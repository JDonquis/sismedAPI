<?php

namespace App\Console\Commands;

use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\InventoryGeneral;
use App\Models\Product;
use App\Services\InventoryService;
use Carbon\Carbon;
use Illuminate\Console\Command;
class VerifyConditionsInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-conditions-inventory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify products conditions in all inventories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        Inventory::whereDate('expiration_date', '<=', $today)
                ->update(['condition_id' => 3]);

        InventoryService::refreshAllInventories();
        
    }
}
