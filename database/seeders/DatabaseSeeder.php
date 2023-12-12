<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MenuSeeder::class);
        $this->call(NewIngredientReasonSeeder::class);
        $this->call(ItemApprovalStatusSeeder::class);
        $this->call(ItemSourcingStatusSeeder::class);
        $this->call(NewIngredientReasonSeeder::class);
        $this->call(NewIngredientTermSeeder::class);
        $this->call(PackagingBeverageTypeSeeder::class);
        $this->call(PackagingDesignSeeder::class);
        $this->call(PackagingMaterialTypeSeeder::class);
        $this->call(PackagingPaperTypeSeeder::class);
        $this->call(PackagingStickerSeeder::class);
        $this->call(PackagingTypeSeeder::class);
        $this->call(PackagingUseSeeder::class);
    }
}
