<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipping;

class TrackingController extends Controller
{
    
    function search(Request $request){

        if($request->tracking != ""){

            $shipping = Shipping::where("tracking", $request->tracking)
            ->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])
            ->with("shippingStatus")->get();
            if($shipping != null){
                return view("tracking", ["shippings" => $shipping, "email" => $request->email]);
            }else{
                return view("tracking");
            }
            

        }else{
            return view("tracking");
        }
        

    }

}
