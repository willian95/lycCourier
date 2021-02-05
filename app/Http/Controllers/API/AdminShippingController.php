<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Http\Requests\APIShippingUpdateRequest;
use Auth;
use App\Shipping;
use App\Traits\StoreShippingHistory;
use App\Traits\SendEmail;
use App\User;
use App\ShippingStatus;

class AdminShippingController extends Controller
{

    use StoreShippingHistory;
    use SendEmail;

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;
            $auth = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();
            if($auth->role_id < 3){
                
                $shippings = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                    $q->with("department", "district", "province");
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();
            
            }else if($auth->role_id == 3){

                $shippings = Shipping::where("reseller_id", $auth->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("reseller_id", $auth->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }
            else if($auth->role_id == 4){

                $shippings = Shipping::where("client_id", $auth->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("client_id", $auth->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }

            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function fetch($page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;
            $auth = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

            if($auth->role_id < 3){

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                    $q->with("department", "district", "province");
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();

                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }
            else if($auth->role_id == 3){

                $shippings = Shipping::where("reseller_id", $auth->id)->with("recipient", "box", "shippingStatus")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("reseller_id", $auth->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }
            else if($auth->role_id == 4){

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->orderBy("id", "desc")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->where("client_id", $auth->id)->get();

                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->where("client_id", $auth->id)->count();

            }
            
            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function update(Request $request){

       

            $shipping = Shipping::find($request->id);
            $shipping->shipping_status_id = $shipping->shipping_status_id + 1;
            $shipping->update();

            $this->storeShippingHistory($shipping->id, $shipping->shipping_status_id + 1);
            //$this->sendEmail($shipping);
            if($shipping->recipient_id != null){
                $recipient = Recipient::find($shipping->recipient_id);
                $to_name = $recipient->name;
                $to_email = $recipient->email;
            }else if($shipping->client_id != null){
                $recipient = User::find($shipping->client_id);
                $to_name = $recipient->name;
                $to_email = $recipient->email;
            }
            
            
            $status = ShippingStatus::find($shipping->shipping_status_id);
    
            $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];
    
            \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping, $status) {
    
                $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." en ".$status->name."!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            return response()->json(["success" => true, "msg" => "Envío Actualizado exitosamente"]);


        

    }

    function fetchByTracking($tracking){

        try{

            $shipping = Shipping::where("tracking", $tracking)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->orderBy("id", "desc")
            ->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
                $q->with("department", "district", "province");
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->first();

            return response()->json(["success" => true, "shipping" => $shipping]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }
}
