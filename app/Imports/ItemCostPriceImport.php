<?php

namespace App\Imports;

use App\ItemMaster;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use CRUDBooster;

class ItemCostPriceImport implements ToModel, WithHeadingRow, WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        $currentItemCode = ItemMaster::where('tasteless_code', $row['tasteless_code'])->first();
        
        $data = [
            'purchase_price' => $row['cost_price'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => CRUDBooster::myId()
        ];

        ItemMaster::where('tasteless_code', '=', (string)$row['tasteless_code'])->update($data);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
