<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\District;

class DistrictController extends Controller
{
    function fetch($department_id, $province_id){
        
        $districts = District::where("province_id", str_pad($province_id, 4, "0", STR_PAD_LEFT))->where("department_id", str_pad($department_id, 2, "0", STR_PAD_LEFT))->get();
        return response()->json(["districts" => $districts]);

    }
}
