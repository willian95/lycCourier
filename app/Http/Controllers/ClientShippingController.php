<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClientShippingRequest;
use App\Http\Requests\ClientShippingUpdateRequest;
use App\Shipping;
use App\ShippingProduct;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use App\AdminMail;

class ClientShippingController extends Controller
{
    
    function create(){
        return view("clients.shippings.create");
    }

    function list(){
        return view("clients.shippings.list");
    }

    function store(ClientShippingRequest $request){

        try{

            $shipping = new Shipping;
            $shipping->tracking = $request->tracking;
            $shipping->client_id = \Auth::user()->id;
            $shipping->address = str_replace("'", "", $request->address);
            $shipping->save();

            foreach($request->products as $product){

                if($product["image"] != null){
                    try{
            
                        $imageData = $product["image"];
    
                        if(strpos($imageData, "svg+xml") > 0){
    
                            $data = explode( ',', $imageData);
                            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                            $ifp = fopen($fileName, 'wb' );
                            fwrite($ifp, base64_decode( $data[1] ) );
                            rename($fileName, 'img/bills/'.$fileName);
            
                        }else{
    
                            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                            Image::make($product['image'])->save(public_path('img/bills/').$fileName);
                        }
            
                    }catch(\Exception $e){
            
                        return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
            
                    }
                }

                $shippingProduct = new ShippingProduct;
                $shippingProduct->name = str_replace("'", "", $product["name"]);
                $shippingProduct->description = str_replace("'", "", $product["description"]);
                $shippingProduct->price = $product["price"];
                $shippingProduct->shipping_id = $shipping->id;
                if($product["image"] != null){
                    $shippingProduct->image = url('/img/bills/')."/".$fileName;
                }
                $shippingProduct->save();


            }

            $data = ["messageTitle" => "Nuevo envío creado", "messageMail" => "El cliente ".\Auth::user()->name." ".\Auth::user()->lastname." ha creado un nuevo envío con el tracking: <strong>".$request->tracking."</strong>", "shippingId" => $shipping->tracking];

            foreach(AdminMail::all() as $admin){

                $to_email = $admin->email;

                \Mail::send("emails.adminNotification", $data, function($message) use ($to_email) {
    
                    $message->to($to_email)->subject("¡Un usuario ha creado un envío!");
                    $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
        
                });

            }

            return response()->json(["success" => true, "msg" => "Envío creado, le notificaremos cuando sea procesado"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function update(ClientShippingUpdateRequest $request){

        try{

            $shipping = Shipping::where("id", $request->id)->first();

            if(Shipping::where("tracking", $request->tracking)->where("client_id", "<>", \Auth::user()->id)->count() == 0){

                $shipping->tracking = $request->tracking;
                $shipping->address = str_replace("'", "", $request->address);
                $shipping->update();

                $shippingProducts = ShippingProduct::where("shipping_id", $shipping->id)->get();
                
                foreach($shippingProducts as $shippingProduct){
                    $exists = false;
                    foreach($request->products as $product){

                        if($shippingProduct->id == $product["id"]){
                            $exists = true;
                        }

                    }

                    if($exists == false){

                        ShippingProduct::where("id", $shippingProduct->id)->first()->delete();

                    }

                }

                foreach($request->products as $product){

                    if(isset($product["id"])){

                        if($product["image"] != null){

                            $imageData = $product["image"];
                            if(base64_encode(base64_decode($imageData, true)) === $imageData){

                                try{
                
                                    if(strpos($imageData, "svg+xml") > 0){
                
                                        $data = explode( ',', $imageData);
                                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                                        $ifp = fopen($fileName, 'wb' );
                                        fwrite($ifp, base64_decode( $data[1] ) );
                                        rename($fileName, 'img/bills/'.$fileName);
                        
                                    }else{
                
                                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                                        Image::make($product['image'])->save(public_path('img/bills/').$fileName);
                                    }
                        
                                }catch(\Exception $e){
                        
                                    return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
                        
                                }

                            }

                        }

                        $shippingProduct = ShippingProduct::where("id", $product["id"])->first();
                        $shippingProduct->name = str_replace("'", "", $product["name"]);
                        $shippingProduct->description = str_replace("'", "", $product["description"]);
                        $shippingProduct->price = $product["price"];
                        $shippingProduct->shipping_id = $shipping->id;
                        if(base64_encode(base64_decode($imageData, true)) === $product["image"]){
                            $shippingProduct->image = url('/img/bills/')."/".$fileName;
                        }
                        $shippingProduct->update();

                    }else{
                        
                        if($product["image"] != null){
                            try{
                    
                                $imageData = $product["image"];
            
                                if(strpos($imageData, "svg+xml") > 0){
            
                                    $data = explode( ',', $imageData);
                                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                                    $ifp = fopen($fileName, 'wb' );
                                    fwrite($ifp, base64_decode( $data[1] ) );
                                    rename($fileName, 'img/bills/'.$fileName);
                    
                                }else{
            
                                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                                    Image::make($product['image'])->save(public_path('img/bills/').$fileName);
                                }
                    
                            }catch(\Exception $e){
                    
                                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
                    
                            }
                        }
    
                        $shippingProduct = new ShippingProduct;
                        $shippingProduct->name = str_replace("'", "", $product["name"]);
                        $shippingProduct->description = str_replace("'", "", $product["description"]);
                        $shippingProduct->price = $product["price"];
                        $shippingProduct->shipping_id = $shipping->id;
                        if($product["image"] != null){
                            $shippingProduct->image = url('/img/bills/')."/".$fileName;
                        }
                        $shippingProduct->save();

                    }

                 


                }

                return response()->json(["success" => true, "msg" => "Envío actualizado"]);

            }else{

                return response()->json(["success" => false, "msg" => "Ya existe un envío con este número de tracking"]);


            }

            

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function fetch($page){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippings = Shipping::skip($skip)->take($dataAmount)->where("client_id", \Auth::user()->id)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
            }])
            ->with("shippingStatus", "shippingHistories", "shippingProducts")->orderBy("id", "desc")->get();
            
            $shippingsCount = Shipping::with("shippingStatus", "shippingHistories", "shippingProducts")->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
            }])
           ->count();

            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $shippings = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->skip($skip)->take($dataAmount)->where("client_id", \Auth::user()->id)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
            }])
            ->with("shippingStatus", "shippingHistories", "shippingProducts")->orderBy("id", "desc")->get();

            $shippingsCount = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->skip($skip)->take($dataAmount)->where("client_id", \Auth::user()->id)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
            }])
            ->with("shippingStatus", "shippingHistories", "shippingProducts")->orderBy("id", "desc")->count();

            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);

        }

    }

    function edit($tracking){

        try{    

            $shipping = Shipping::where("tracking", $tracking)->with("shippingProducts")->first();
            return view("clients.shippings.edit", ["shipping" => $shipping]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

}
