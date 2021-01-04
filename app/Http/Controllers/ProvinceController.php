<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province;

class ProvinceController extends Controller
{
    function fetch($department_id){

        $provinces = Province::where("department_id", str_pad($department_id, 2, "0", STR_PAD_LEFT))->get();
        return response()->json(["provinces" => $provinces]);

    }
}
