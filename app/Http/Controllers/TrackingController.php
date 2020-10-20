<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipping;

class TrackingController extends Controller
{
    
    function search(Request $request){

        if($request->tracking != ""){

            $shipping = Shipping::where("tracking", $request->tracking)->with("recipient")->with("box")->with("shippingStatus")->first();
            if($shipping != null){
                return view("tracking", ["shipping" => $shipping]);
            }else{
                return view("tracking");
            }
            

        }else{
            return view("tracking");
        }
        

    }

}
