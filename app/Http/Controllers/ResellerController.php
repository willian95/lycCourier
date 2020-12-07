<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ResellerController extends Controller
{
    function fetch(){

        try{    

            $users = User::where("role_id", 3)->get();
            return response()->json(["resellers" => $users]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }
}
