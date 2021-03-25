<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShippingGuide;
use App\Http\Requests\ShippingGuideStoreRequest;
use App\Http\Requests\ShippingGuideUpdateRequest;
use App\Shipping;

class ShippingGuideController extends Controller
{
    
    function index(){

        return view("shippingGuides.index");

    }

    function create(){

        return view("shippingGuides.create");

    }

    function edit($shippingGuide){

        $shippingGuide = ShippingGuide::find($shippingGuide);
        $shippings = Shipping::where("shipping_guide_id", $shippingGuide->id)->get();
        return view("shippingGuides.edit", ["shippingGuide" => $shippingGuide, "shippings" => $shippings]);

    }

    function fetch($page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippingGuides = ShippingGuide::skip($skip)->take($dataAmount)->with("shippings")->get();
            $shippingGuidesCount =  ShippingGuide::with("shippings")->count();

            return response()->json(["shippingGuides" => $shippingGuides, "shippingGuidesCount" => $shippingGuidesCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);

        }

    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $shippingGuides = ShippingGuide::where("guide", "like", '%'.$request->search.'%')->skip($skip)->take($dataAmount)->get();
            $shippingGuidesCount =  ShippingGuide::where("guide", "like", '%'.$request->search.'%')->count();

            return response()->json(["shippingGuides" => $shippingGuides, "shippingGuidesCount" => $shippingGuidesCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);

        }

    }

    function store(ShippingGuideStoreRequest $request){

        try{

            $guide = new ShippingGuide;
            $guide->guide = $request->guide;
            $guide->save();

            foreach($request->shippings as $shipping){

                $shippingModel = Shipping::find($shipping);
                $shippingModel->shipping_guide_id = $guide->id;
                $shippingModel->update();

            }

            return response()->json(["success" => true, "msg" => "Guía creada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }



    }

    function update(ShippingGuideUpdateRequest $request){

        try{

            if(ShippingGuide::where("id", "<>", $request->id)->where("guide", $request->guide)->count() > 0){
                return response()->json(["success" => false, "msg" => "Esta número de guía ya existe"]);
            }

            $guide = ShippingGuide::find($request->id);
            $guide->guide = $request->guide;
            $guide->update();

            Shipping::where("shipping_guide_id", $request->id)->update([
                "shipping_guide_id" => null
            ]);

            foreach($request->shippings as $shipping){

                $shippingModel = Shipping::find($shipping);
                $shippingModel->shipping_guide_id = $guide->id;
                $shippingModel->update();

            }

            return response()->json(["success" => true, "msg" => "Guía actualizada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }



    }

    function delete(Request $request){

        try{

            $shippingGuide = ShippingGuide::find($request->id);

            Shipping::where("shipping_guide_id", $shippingGuide->id)->update(["shipping_guide_id" => null]);
            
            $shippingGuide->guide = $shippingGuide."-".uniqid();
            $shippingGuide->update();

            $shippingGuide->delete();

            return response()->json(["success" => true, "msg" => "Guía eliminada"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

}
