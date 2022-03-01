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
        
        $realDate = "";
        $index = 0;
        foreach ($rows as $row){
            
            
            if($index == 2){
                
                foreach($row as $dateSearch){
                    
                    if(strpos($dateSearch, "DATE") == 0){
                        $date = str_replace("DATE: ", "",$dateSearch);
                        if($date != ""){
                            $realDate = $date;
                        }
                        
                    }

                }
                
                
            }
            
            if($index > 8 && $row[1]){ 
                $duaNew = new DuaNew;
                $duaNew->hawb = $row[1];
                $duaNew->consignee = $row[2];
                $duaNew->client = $row[4];
                $duaNew->description = $row[5];
                $duaNew->pieces = $row[6];
                $duaNew->weight = $row[7];
                $duaNew->address = $row[8];
                $duaNew->category = $row[9];
                $duaNew->value = $row[10];
                $duaNew->document = $row[11];
                $duaNew->warehouse = $row[12];
                $duaNew->real_date = $realDate;
                $duaNew->save();
                

            }
            $index++;
        }



    }

}
