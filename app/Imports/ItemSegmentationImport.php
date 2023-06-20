<?php

namespace App\Imports;

use DB;
use App\ItemMasterApproval;
use App\Segmentation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ItemSegmentationImport implements ToModel, WithHeadingRow, WithChunkReading
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {  
        $sku_datas = DB::table('sku_legends') 
            ->where('status', 'ACTIVE')
            ->pluck('sku_legend')
            ->toArray();
        $data_segments = array();
        $segment_cols = self::getActiveSkuLegend();

        foreach($segment_cols as $k_seg => $seg){
            if(!is_null($row[$seg])){
                if(in_array($row[$seg],$sku_datas)){
                    $data_segments[$k_seg] = $row[$seg];
                }                    
            }
        }

        $data_segments['approval_status'] = '202';


        ItemMasterApproval::where('tasteless_code', '=', (string)$row['tasteless_code'])->update($data_segments);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function getActiveSkuLegend(){
        $segment_columns = array();
        $segments = Segmentation::where('status','ACTIVE')->orderBy('segment_column_description','ASC')->get();
        foreach($segments as $segment){
                        
            $segment_header = $segment->segment_column_description;
            $l_header = str_replace(' ', '_', strtolower($segment_header)); 
            $a_header =  str_replace("'", "", $l_header); 
            $f_header =  str_replace('-', '_', $a_header);
            $segment_columns[$segment->segment_column_name ] = $f_header;
       }
       return $segment_columns;
    }
}
