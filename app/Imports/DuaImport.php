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

                $date = str_replace("DATE: ", "",$row[7]);
                $date = str_replace("/", "-", $date);
                $date = strtotime($date);
                $date = date('Y-m-d',$date);

            }
            
            if($index > 8 && $row[1]){ 

                $numberGuide = str_replace("LYC", "", $row[1]);
                $guide = ShippingGuide::where("guide", intval($numberGuide))->first();

                if($guide){
                    $duaNew = new DuaNew;
                    $duaNew->shipping_guide_id = $guide->id;
                    $duaNew->hawb = $row[1];
                    $duaNew->client = $row[3];
                    $duaNew->pieces = $row[5];
                    $duaNew->weight = floatval($row[6]);
                    $duaNew->arrivalDate = $date;
                    $duaNew->save();
                }
                

            }
            $index++;
        }



    }

}
