<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShippingStoreRequest;
use App\Http\Requests\ShippingUpdateRequest;
use App\Traits\StoreShippingHistory;
use App\Shipping;
use App\ShippingStatus;

class ShippingController extends Controller
{
    use StoreShippingHistory;
    
    function index(){
        return view("shippings.list");
    }

    function create(){
        return view("shippings.create.index");
    }

    function store(ShippingStoreRequest $request){

        try{

            $shipping = new Shipping;
            $shipping->tracking = $request->tracking;
            $shipping->recipient_id = $request->recipientId;
            $shipping->box_id = $request->packageId;
            $shipping->pieces = $request->pieces;
            $shipping->length = $request->length;
            $shipping->height = $request->height;
            $shipping->weight = $request->weight;
            $shipping->width = $request->width;
            $shipping->description = $request->description;
            $shipping->save();

            $this->storeShippingHistory($shipping->id, 1);

            return response()->json(["success" => true, "msg" => "Envío realizado exitosamente"]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function update(ShippingUpdateRequest $request){

        try{

            $shipping = Shipping::find($request->id);
            $shipping->shipping_status_id = $request->status;
            $shipping->update();

            $this->storeShippingHistory($shipping->id, $request->status);

            return response()->json(["success" => true, "msg" => "Envío Actualizado exitosamente"]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function fetch($page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus")->get();
            $shippingsCount = Shipping::count();

            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function show($tracking){
    
        $shipping = Shipping::where("tracking", $tracking)->firstOrFail();
        return view("shippings.show", ["shipping" => $shipping]);

    }

    function getAllStatuses(){

        $statuses = ShippingStatus::all();
        return response()->json(["statuses" => $statuses]);

    }

}
