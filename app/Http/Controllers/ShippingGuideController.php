<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShippingGuide;
use App\Http\Requests\ShippingGuideStoreRequest;
use App\Http\Requests\ShippingGuideUpdateRequest;
use App\Shipping;
use App\ShippingGuideShipping;
use PDF;

class ShippingGuideController extends Controller
{
    
    function index(){

        return view("shippingGuides.index");

    }

    function create(){

        return view("shippingGuides.create");

    }

    function edit($shippingGuide){

        $shippings = [];
        $shippingGuide = ShippingGuide::find($shippingGuide);
        $shippingGuideShippings = ShippingGuideShipping::where("shipping_guide_id", $shippingGuide->id)->with("shipping")->get();
        
        foreach($shippingGuideShippings as $shipping){
            $shippings[] = $shipping->shipping;
        }

        return view("shippingGuides.edit", ["shippingGuide" => $shippingGuide, "shippings" => json_encode($shippings)]);

    }

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
            })->skip($skip)->take($dataAmount)->orderBy("id", "desc")->with("shippingGuideShipping", "shippingGuideShipping.shipping")->get();
            $shippingGuidesCount =  ShippingGuide::where("guide", "like", '%'.$request->search.'%')->orWhereHas("shippingGuideShipping.shipping", function($q) use($request){
                $q->where("warehouse_number", "like", '%'.$request->search.'%');
            })->with("shippingGuideShipping", "shippingGuideShipping.shipping")->count();

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

                $shippingGuideShipping = new ShippingGuideShipping;
                $shippingGuideShipping->shipping_guide_id = $guide->id;
                $shippingGuideShipping->shipping_id = $shipping;
                $shippingGuideShipping->save();

                $shippingModel = Shipping::find($shipping);
                $shippingModel->shipping_status_id = 2;
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

            $shippingGuideShippings = ShippingGuideShipping::where("shipping_guide_id", $request->id)->get();
            foreach($shippingGuideShippings as $shipping){
                $shipping->delete();
            }

            foreach($request->shippings as $shipping){

                $shippingGuideShipping = new ShippingGuideShipping;
                $shippingGuideShipping->shipping_guide_id = $guide->id;
                $shippingGuideShipping->shipping_id = $shipping;
                $shippingGuideShipping->save();

            }

            return response()->json(["success" => true, "msg" => "Guía actualizada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }



    }

    function delete(Request $request){

        try{

            $shippingGuide = ShippingGuide::find($request->id);

            $shippingGuideShippings = ShippingGuideShipping::where("shipping_guide_id", $request->id)->get();
            foreach($shippingGuideShippings as $shipping){
                $shipping->delete();
            }
            
            $shippingGuide->guide = $shippingGuide->guide."-".uniqid();
            $shippingGuide->update();
            $shippingGuide->delete();

            return response()->json(["success" => true, "msg" => "Guía eliminada"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function generatePDFFile($guideId){

     

            $shippingGuideShippings = ShippingGuideShipping::where("shipping_guide_id", $guideId)->with("shipping", "shipping.client")->whereHas("shipping", function($q){
                $q->orderBy("tracking", "desc")->orderBy("created_at", "desc");
            })->get();

            $pdf = PDF::loadView('pdf.shippingGuide', ["shippingGuideShippings" => $shippingGuideShippings]);
            return $pdf->stream('shipping-guide-'.uniqid().'.pdf');

       


    }

}
