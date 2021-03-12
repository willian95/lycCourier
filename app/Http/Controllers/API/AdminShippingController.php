<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ShippingStoreRequest;
use App\Http\Requests\ShippingUpdateInfoRequest;
//use App\Http\Requests\APIShippingUpdateRequest;
use Auth;
use App\Shipping;
use App\Traits\StoreShippingHistory;
use App\Traits\SendEmail;
use App\User;
use App\ShippingStatus;
use App\ShippingProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

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

            $dataAmount = 10;
            $skip = ($page - 1) * $dataAmount;
            $auth = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

            if($auth->role_id < 3){

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingProducts")->orderBy("id", "desc")
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

                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingProducts")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
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

            $shipping = Shipping::where("tracking", $tracking)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingProducts")->orderBy("id", "desc")
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

    function store(ShippingStoreRequest $request){

        try{

            $shipping = new Shipping;
            $shipping->tracking = $request->tracking;
            $shipping->client_id = $request->recipientId;
            $shipping->box_id = $request->packageId;
            $shipping->pieces = $request->pieces;
            $shipping->length = $request->length;
            $shipping->height = $request->height;
            $shipping->weight = $request->weight;
            $shipping->width = $request->width;
            $shipping->reseller_id = $request->resellerId;
            $shipping->shipping_status_id = 1;
            $shipping->description = $request->description;
            $shipping->is_finished = 1;
            $shipping->shipped_at = Carbon::now();
            $shipping->address = str_replace("'", "", $request->address);

            if(isset($request->warehouseNumber)){
                $shipping->warehouse_number = $request->warehouseNumber;
            }

            $shipping->save();

            $this->warehouseUpdate($request, $shipping);
            $this->shippingProductImageStore($request, $shipping);
            $this->clientUpdate($request);

            $this->storeShippingHistory($shipping->id, 1);
            //$this->sendEmail($shipping);
            $recipient = User::find($shipping->client_id);
           
            
            $status = ShippingStatus::find($shipping->shipping_status_id);

            if(isset($request->resellerId)){

                $to_name = User::find($request->resellerId)->name;
                $to_email = User::find($request->resellerId)->email;

                if(filter_var($to_email, FILTER_VALIDATE_EMAIL)){
                    $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking, "clientName" => $recipient->name];
    
                    \Mail::send("emails.resellerNotification", $data, function($message) use ($to_name, $to_email, $shipping) {
                        
                        
                        $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." creado!");
                        $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
            
                    });
                }

                

            }

            $to_name = $recipient->name;
            $to_email = $recipient->email;
            
            if(filter_var($to_email, FILTER_VALIDATE_EMAIL)){
                $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];
    
                \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping) {
        
                    $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." creado!");
                    $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
        
                });
            }

            return response()->json(["success" => true, "msg" => "Envío realizado exitosamente"]);
            
        }catch(\Exception $e){
            //
            Log::info($e->getMessage()." "."ln: ".$e->getLine());
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function warehouseUpdate($request, $shipping){
        if(!isset($request->warehouseNumber)){

            $warehouseIndex = Shipping::orderBy("warehouse_index", "desc")->first()->warehouse_index;
            $shipping->warehouse_index = $warehouseIndex + 1;
            $shipping->warehouse_number = "WRI".str_pad($shipping->warehouse_index, 7, "0", STR_PAD_LEFT);
            $shipping->update();

        }
    }

    function shippingProductImageStore($request, $shipping){
        foreach($request->products as $product){

            $fileType = "image";
            if($product["image"] != null && $product["image"] != "Sin factura"){
                try{
        
                    $imageData = $product["image"];

                    if(strpos($imageData, "svg+xml") > 0){
                        $fileType = "image";
                        $data = explode( ',', $imageData);
                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                        $ifp = fopen($fileName, 'wb' );
                        fwrite($ifp, base64_decode( $data[1] ) );
                        rename($fileName, 'img/bills/'.$fileName);
        
                    }if(strpos($imageData, "/pdf") > 0){
                        $fileType = "pdf";
                        $data = explode( ',', $imageData);
                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."pdf";
                        $ifp = fopen($fileName, 'wb' );
                        fwrite($ifp, base64_decode( $data[1] ) );
                        rename($fileName, 'img/bills/'.$fileName);
        
                    }else{
                        $fileType = "image";
                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                        Image::make($product['image'])->save(public_path('img/bills/').$fileName);
                    }
        
                }catch(\Exception $e){
        
                    return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        
                }
            }

            if($product["productImage"] != null){
                try{
        
                    $productImageData = $product["image"];

                    if(strpos($productImageData, "svg+xml") > 0){
                        $data = explode( ',', $productImageData);
                        $productFileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                        $ifp = fopen($productFileName, 'wb' );
                        fwrite($ifp, base64_decode( $data[1] ) );
                        rename($productFileName, 'img/bills/'.$productFileName);
        
                    }else{
                        $productFileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($productImageData, 0, strpos($productImageData, ';')))[1])[1];
                        Image::make($product['productImage'])->save(public_path('img/bills/').$productFileName);
                    }
        
                }catch(\Exception $e){
        
                    return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        
                }
            }
            

            $shippingProduct = new ShippingProduct;
            $shippingProduct->name = str_replace("'", "", $product["name"]);
            $shippingProduct->price = $product["price"];
            $shippingProduct->shipping_id = $shipping->id;

            if($product["productImage"] != null){

                $shippingProduct->productImage = url('/img/bills/')."/".$productFileName;

            }

            if($product["image"] != null){
                $shippingProduct->file_type = $fileType;
            }
            if($product["image"] != null && $product["image"] != "Sin factura"){
                $shippingProduct->image = url('/img/bills/')."/".$fileName;
            }else{
                $shippingProduct->image = $product["image"];
            }
            $shippingProduct->save();


        }
    }

    function clientUpdate($request){
        if($request->get('dniPicture') != null){
            try{
    
                $imageData = $request->get('dniPicture');

                if(strpos($imageData, "svg+xml") > 0){

                    $data = explode( ',', $imageData);
                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                    $ifp = fopen($fileName, 'wb' );
                    fwrite($ifp, base64_decode( $data[1] ) );
                    rename($fileName, 'img/clients/'.$fileName);
    
                }else{

                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                    Image::make($request->get('dniPicture'))->save(public_path('img/clients/').$fileName);
                }
    
            }catch(\Exception $e){
    
                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
    
            }
        }

        if($request->get('dniPictureBack') != null){
            try{
    
                $imageData = $request->get('dniPictureBack');

                if(strpos($imageData, "svg+xml") > 0){

                    $data = explode( ',', $imageData);
                    $fileNameBack = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                    $ifp = fopen($fileNameBack, 'wb' );
                    fwrite($ifp, base64_decode( $data[1] ) );
                    rename($fileNameBack, 'img/clients/'.$fileNameBack);
    
                }else{

                    $fileNameBack = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                    Image::make($request->get('dniPictureBack'))->save(public_path('img/clients/').$fileNameBack);
                }
    
            }catch(\Exception $e){
    
                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
    
            }
        }

        $client = User::find($request->recipientId);
        if($request->get('dniPicture') != null){
            $client->dni_picture = url('img/clients')."/".$fileName;
        }
        if($request->get('dniPictureBack') != null){
            $client->dni_picture_back = url('img/clients')."/".$fileNameBack;
        }
        $client->department_id = $request->department;
        $client->province_id = $request->province;
        $client->district_id = $request->district;
        $client->address = $request->address;
        $client->dni = $request->clientDNI;

        if(isset($request->resellerId)){
            $client->reseller_id = $request->resellerId;
        }

        $client->update();
    }

    function updateInfo(ShippingUpdateInfoRequest $request){

        try{

            //if(Shipping::where("tracking", $request->tracking)->where("id", "<>", $request->shippingId)->count() == 0){
                $shipping = Shipping::find($request->shippingId);
                $shipping->client_id = $request->recipientId;
                $shipping->box_id = $request->packageId;
                $shipping->pieces = $request->pieces;
                $shipping->length = $request->length;
                $shipping->height = $request->height;
                $shipping->weight = $request->weight;
                $shipping->width = $request->width;
                $shipping->reseller_id = $request->resellerId;
                $shipping->description = $request->description;
                $shipping->description = str_replace("'", "", $request->description);
                $shipping->address = str_replace("'", "", $request->address);
                $shipping->update();

                $this->storeShippingHistory($shipping->id, $shipping->shipping_status_id);

                $shippingProducts = ShippingProduct::where("shipping_id", $shipping->id)->get();
                foreach($shippingProducts as $shippingProduct){
                    $exists = false;
                    foreach($request->products as $product){
                        if(isset($product["id"]))
                        {
                            if($shippingProduct->id == $product["id"]){
                                $exists = true;
                            }
                        }

                    }

                    if($exists == false){

                        ShippingProduct::where("id", $shippingProduct->id)->first()->delete();

                    }

                }

                foreach($request->products as $product){
                    $fileType = "image";
                    $fileName = "";
                    if(isset($product["id"])){

                        if($product["image"] != null){

                            $imageData = $product["image"];
                           
                            if(strpos($imageData, "base64") > 0){
                               
                                try{
                
                                    if(strpos($imageData, "svg+xml") > 0){
                
                                        $data = explode( ',', $imageData);
                                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                                        $ifp = fopen($fileName, 'wb' );
                                        fwrite($ifp, base64_decode( $data[1] ) );
                                        rename($fileName, 'img/bills/'.$fileName);
                        
                                    }else if(strpos($imageData, "/pdf") > 0){
                                        
                                        $fileType = "pdf";
                                        $data = explode( ',', $imageData);
                                        $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."pdf";
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

                        if($product["productImage"] != null){
                            try{
                    
                                $productImageData = $product["image"];
            
                                if(strpos($productImageData, "svg+xml") > 0){
                                    $data = explode( ',', $productImageData);
                                    $productFileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                                    $ifp = fopen($productFileName, 'wb' );
                                    fwrite($ifp, base64_decode( $data[1] ) );
                                    rename($productFileName, 'img/bills/'.$productFileName);
                    
                                }else{
                                    $productFileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($productImageData, 0, strpos($productImageData, ';')))[1])[1];
                                    Image::make($product['productImage'])->save(public_path('img/bills/').$productFileName);
                                }
                    
                            }catch(\Exception $e){
                    
                                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
                    
                            }
                        }

                        $shippingProduct = ShippingProduct::where("id", $product["id"])->first();
                        $shippingProduct->name = str_replace("'", "", $product["name"]);
                        $shippingProduct->price = $product["price"];
                        $shippingProduct->shipping_id = $shipping->id;

                        if($product["productImage"] != null){

                            $shippingProduct->productImage = url('/img/bills/')."/".$productFileName;
            
                        }
                     
                        if($fileName != ""){
                            $shippingProduct->image = url('/img/bills/')."/".$fileName;
                            $shippingProduct->file_type = $fileType;
                        }

                        $shippingProduct->update();

                    }else{
                        
                        if($product["image"] != null){
                            
                            $fileType = "image";
                            $fileName = "";

                            try{
                    
                                $imageData = $product["image"];
            
                                if(strpos($imageData, "svg+xml") > 0){
            
                                    $data = explode( ',', $imageData);
                                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                                    $ifp = fopen($fileName, 'wb' );
                                    fwrite($ifp, base64_decode( $data[1] ) );
                                    rename($fileName, 'img/bills/'.$fileName);
                    
                                }if(strpos($imageData, "/pdf") > 0){
                                    $fileType = "pdf";
                                    $data = explode( ',', $imageData);
                                    $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."pdf";
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

                        if($product["productImage"] != null){
                            try{
                    
                                $productImageData = $product["image"];
            
                                if(strpos($productImageData, "svg+xml") > 0){
                                    $data = explode( ',', $productImageData);
                                    $productFileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                                    $ifp = fopen($productFileName, 'wb' );
                                    fwrite($ifp, base64_decode( $data[1] ) );
                                    rename($productFileName, 'img/bills/'.$productFileName);
                    
                                }else{
                                    $productFileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($productImageData, 0, strpos($productImageData, ';')))[1])[1];
                                    Image::make($product['productImage'])->save(public_path('img/bills/').$productFileName);
                                }
                    
                            }catch(\Exception $e){
                    
                                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
                    
                            }
                        }
    
                        $shippingProduct = new ShippingProduct;
                        $shippingProduct->name = str_replace("'", "", $product["name"]);
                        $shippingProduct->price = $product["price"];
                        $shippingProduct->shipping_id = $shipping->id;

                        if($product["productImage"] != null){

                            $shippingProduct->productImage = url('/img/bills/')."/".$productFileName;
            
                        }

                        if($product["image"] != null){
                            $shippingProduct->image = url('/img/bills/')."/".$fileName;
                        }
                        if($product["image"] != null){
                            $shippingProduct->file_type = $fileType;
                        }
                        $shippingProduct->save();

                    }

                }

                if($request->get('dniPicture') != null){
                    try{
            
                        $imageData = $request->get('dniPicture');
    
                        if(strpos($imageData, "svg+xml") > 0){
    
                            $data = explode( ',', $imageData);
                            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                            $ifp = fopen($fileName, 'wb' );
                            fwrite($ifp, base64_decode( $data[1] ) );
                            rename($fileName, 'img/clients/'.$fileName);
            
                        }else{
    
                            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                            Image::make($request->get('dniPicture'))->save(public_path('img/clients/').$fileName);
                        }
            
                    }catch(\Exception $e){
            
                        return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
            
                    }
                }
    
                $client = User::find($request->recipientId);
                if($request->get('dniPicture') != null){
                    $client->dni_picture = url('img/clients')."/".$fileName;
                }
                $client->department_id = $request->department;
                $client->province_id = $request->province;
                $client->district_id = $request->district;
                $client->address = $request->address;
                $client->dni = $request->clientDNI;
                if(isset($request->resellerId)){
                    $client->reseller_id = $request->resellerId;
                }
                $client->update();


            //}

            return response()->json(["success" => true, "msg" => "Envío actualizado exitosamente"]);
            
        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }
        
    }

}
