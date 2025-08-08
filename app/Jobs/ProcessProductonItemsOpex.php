<?php

namespace App\Jobs;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProductonItemsOpex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $id;
    protected $gas_cost;
    protected $water;
    protected $meralco;
    protected $storage_cost;

    public function __construct($id, $gas_cost, $water, $meralco, $storage_cost)
    {
        $this->id = $id;
        $this->gas_cost = $gas_cost;
        $this->water = $water;
        $this->meralco = $meralco;
        $this->storage_cost = $storage_cost; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $id = $this->id;    
        $gas_cost = $this->gas_cost;
        $water = $this->water; 
        $meralco = $this->meralco; 
        $storage_cost = $this->storage_cost; 

        echo 'Memory usage at chunk start: ' . round(memory_get_usage() / 1024 / 1024, 2) . " MB\n";

        // DB::table('production_items')
        // ->where('opex_category', $id)->orderBy('id')->chunkById(1000, function($items) use ($gas_cost, $water, $meralco, $storage_cost) {
        //     foreach ($items as $item) {
        //         DB::table('production_items')->where('id', $item->id)->update([
        //             'gas_cost' => $gas_cost,
        //             'gas_costxfc' => $gas_cost / 100 * $item->ingredient_cost * 100,
        //             'water' => $water,
        //             'waterxfc' => $water / 100 * $item->ingredient_cost * 100,
        //             'meralco' => $meralco,
        //             'meralcoxfc' => $meralco / 100 * $item->ingredient_cost * 100,
        //             'storage_cost' => $storage_cost,
        //             'storage_costxfc' => $storage_cost / 100 * $item->ingredient_cost * 100,
        //             'opex' => ($gas_cost / 100 * $item->ingredient_cost) + ($water / 100 * $item->ingredient_cost) + ($meralco / 100 * $item->ingredient_cost) + ($storage_cost / 100 * $item->ingredient_cost),
        //         ]);
        //     }
        // });
 
        DB::update("UPDATE production_items SET 
            gas_cost = ?, 
            gas_costxfc = ? / 100 * ingredient_cost * 100,
            water = ?, 
            waterxfc = ? / 100 * ingredient_cost * 100,
            meralco = ?, 
            meralcoxfc = ? / 100 * ingredient_cost * 100,
            storage_cost = ?, 
            storage_costxfc = ? / 100 * ingredient_cost * 100,
            opex = gas_costxfc / 100 +`waterxfc` / 100 +`meralcoxfc` / 100 +`storage_costxfc` / 100
            where opex_category = ?
            ",
            [$gas_cost, $gas_cost, $water, $water, $meralco, $meralco, $storage_cost, $storage_cost, $id]
        );
         echo 'Memory usage at chunk end: ' . round(memory_get_usage() / 1024 / 1024, 2) . " MB\n";
    }
}
