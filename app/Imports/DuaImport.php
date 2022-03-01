<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Log;
use App\ShippingGuide;
use App\DuaNew;

class DuaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        
        $index = 0;
        foreach ($rows as $row){
            
            
            if($index == 2){
                
                $date = str_replace("DATE: ", "",$row[5]);
                $realDate = $date;
                
            }
            
            if($index > 8 && $row[1]){ 

             
                $duaNew = new DuaNew;
                $duaNew->shipping_guide_id = ShippingGuide::first()->id;
                $duaNew->hawb = $row[1];
                $duaNew->consignee = $row[2];
                $duaNew->client = $row[3];
                $duaNew->description = $row[4];
                $duaNew->pieces = $row[5];
                $duaNew->weight = floatval($row[6]);
                $duaNew->address = $row[7];
                $duaNew->category = $row[8];
                $duaNew->value = $row[9];
                $duaNew->document = $row[10];
                $duaNew->warehouse = $row[11];
                $duaNew->real_date = $realDate;
                $duaNew->arrivalDate = "2022-01-01";
                $duaNew->save();
                

            }
            $index++;
        }



    }

}
