<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Box;

class BoxController extends Controller
{
    
    function all(){

        try{

            $boxes = Box::all();

            return response()->json(["boxes" => $boxes]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }

    }

}
