<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShippingHistory;

class BinnacleController extends Controller
{
    
    function index(){
        return view("binnacle.index");
    }

    function fetch(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $query = ShippingHistory::with("shipping", "shippingStatus")
            ->with(['shipping.box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['shipping.recipient' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['user' => function ($q) {
                $q->withTrashed();
            }])
            ->orderBy("id", "desc");

            $logs = $query->get();
            $logsCount = $query->take($dataAmount)->skip($skip)->count();

            return response()->json(["success" => true, "logs" => $logs, "logsCount" => $logsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $query = ShippingHistory::whereHas("shipping", function($q) use($request){

                $q->where("tracking", "like", '%'.$request->search.'%');
                $q->orWhere("warehouse_number", "like", '%'.$request->search.'%');

            })
            ->orWhereHas("user", function($q) use($request){
                $q->orWhere("name", "like", '%'.$request->search.'%');
            })
            ->with(['user' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['shipping.box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['shipping.recipient' => function ($q) {
                $q->withTrashed();
            }])
            ->with("shippingStatus")
            ->take($dataAmount)->skip($skip)->orderBy("id", "desc");

           $logs = $query->get();
           $logsCount = $query->count();

            return response()->json(["success" => true, "logs" => $logs, "logsCount" => $logsCount, "dataAmount" => $dataAmount]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

}
