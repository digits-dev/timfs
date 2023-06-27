<?php

namespace App\Imports;

use App\ItemMasterApproval;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use CRUDBooster;

class SalesPriceImport implements ToModel, WithHeadingRow, WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        $currentItemCode = ItemMasterApproval::where('tasteless_code', $row['tasteless_code'])->first();
        if($row['sales_price'] != 0){
            $commi_margin = ($row['sales_price'] - $currentItemCode->landed_cost)/$row['sales_price'];
        }else{
            $commi_margin = 0.00;
        }

        $data = [
            'old_ttp' => $currentItemCode->ttp,
            'action_type' => 'UPDATE',
            'old_ttp_percentage' => $currentItemCode->ttp_percentage,
            'ttp_price_change' => $row['sales_price'],
            'ttp_percentage_price_change' => $commi_margin,
            'ttp_price_effective_date' => date('Y-m-d', strtotime((string)$row['sales_price_effective_date'])),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => CRUDBooster::myId(),
            'approval_status' => '202',
        ];

        ItemMasterApproval::where('tasteless_code', '=', (string)$row['tasteless_code'])->update($data);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
