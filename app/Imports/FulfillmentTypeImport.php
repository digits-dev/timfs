<?php

namespace App\Imports;

use App\ItemMasterApproval;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use CRUDBooster;
use DB;

class FulfillmentTypeImport implements ToModel, WithHeadingRow, WithChunkReading
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
        
        $fulfillment_types = DB::table('fulfillment_methods')
            ->where('status', 'ACTIVE')
            ->get()
            ->toArray();

        $fulfillment_types = array_column($fulfillment_types, 'id', 'fulfillment_method');

        $data = [
            'fulfillment_type_id' => $fulfillment_types[$row['fulfillment_type']],
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
