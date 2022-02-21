<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Shipping;
use App\ShippingGuide;

class ShippingGuideController extends Controller
{
    function fetch($page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippingGuides = ShippingGuide::skip($skip)->take($dataAmount)->with("shippingGuideShipping", "shippingGuideShipping.shipping")->orderBy("id", "desc")->get();
            $shippingGuidesCount =  ShippingGuide::with("shippingGuideShipping", "shippingGuideShipping.shipping")->count();

            return response()->json(["shippingGuides" => $shippingGuides, "shippingGuidesCount" => $shippingGuidesCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);

        }

    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $shippingGuides = ShippingGuide::where("guide", "like", '%'.$request->search.'%')->orWhereHas("shippingGuideShipping.shipping", function($q) use($request){
                $q->where("warehouse_number", "like", '%'.$request->search.'%');
            })->skip($skip)->take($dataAmount)->with("shippingGuideShipping", "shippingGuideShipping.shipping")->get();
            $shippingGuidesCount =  ShippingGuide::where("guide", "like", '%'.$request->search.'%')->orWhereHas("shippingGuideShipping.shipping", function($q) use($request){
                $q->where("warehouse_number", "like", '%'.$request->search.'%');
            })->with("shippingGuideShipping", "shippingGuideShipping.shipping")->count();

            return response()->json(["shippingGuides" => $shippingGuides, "shippingGuidesCount" => $shippingGuidesCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);

        }

    }
}
