<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShippingStoreRequest;
use App\Http\Requests\ShippingUpdateRequest;
use App\Http\Requests\ShippingUpdateInfoRequest;
use App\Http\Requests\ShippingPendingUpdateRequest;
use App\Traits\StoreShippingHistory;
use App\Traits\SendEmail;
use App\Shipping;
use App\ShippingProduct;
use App\ShippingStatus;
use App\Recipient;
use PDF;
use Carbon\Carbon;
use App\Jobs\SendUpdateEmail;
use Intervention\Image\Facades\Image;
use App\Http\Requests\ShippingProcessRequest;
use App\User;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShippingsExport;

class ShippingController extends Controller
{
    use StoreShippingHistory;
    use SendEmail;
    
    function index(){
        return view("shippings.list");
    }

    function create(){
   
        return view("shippings.create.index");

    }

    function isEmail($email) {
        if(preg_match("/^[A-z0-9\\._-]+@[A-z0-9][A-z0-9-]*(\\.[A-z0-9_-]+)*\\.([A-z]{2,6})$/", $email)) {
             return true;
        } else {
             return false;
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


            if(!isset($request->warehouseNumber)){

                $warehouseIndex = Shipping::orderBy("warehouse_index", "desc")->first()->warehouse_index;
                $shipping->warehouse_index = $warehouseIndex + 1;
                $shipping->warehouse_number = "WRI".str_pad($shipping->warehouse_index, 7, "0", STR_PAD_LEFT);
                $shipping->update();

            }
            

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
            
                        $productImageData = $product["productImage"];
    
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
            

            $this->storeShippingHistory($shipping->id, 1);
            //$this->sendEmail($shipping);
            $recipient = User::find($shipping->client_id);
           
            
            $status = ShippingStatus::find($shipping->shipping_status_id);

            if(isset($request->resellerId)){

                $to_name = User::find($request->resellerId)->name;
                $to_email = User::find($request->resellerId)->email;

                if($this->isEmail($to_email)){
                    
                    $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking, "clientName" => $recipient->name];
    
                    \Mail::send("emails.resellerNotification", $data, function($message) use ($to_name, $to_email, $shipping) {
                        
                        
                        $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." creado!");
                        $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
            
                    });
                }

                

            }

            $to_name = $recipient->name;
            $to_email = $recipient->email;
            
            if($this->isEmail($to_email)){

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
                $shipping->tracking = $request->tracking;
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
                    
                                $productImageData = $product["productImage"];
            
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
                     
                        if($fileName != ""){
                            $shippingProduct->image = url('/img/bills/')."/".$fileName;
                            $shippingProduct->file_type = $fileType;
                        }

                        if($productFileName != ""){
                            $shippingProduct->productImage = url('/img/bills/')."/".$productFileName;
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
                    
                                $productImageData = $product["productImage"];
            
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


            //}else{
                //return response()->json(["success" => false, "msg" => "Este tracking ya lo posee otro envío"]);
            //}

            return response()->json(["success" => true, "msg" => "Envío actualizado exitosamente"]);
            
        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }
        
    }

    function update(ShippingUpdateRequest $request){

        try{

            $shipping = Shipping::find($request->id);
            $shipping->shipping_status_id = $request->status;
            $shipping->update();

            $status = ShippingStatus::find($shipping->shipping_status_id);

            $this->storeShippingHistory($shipping->id, $request->status);
            //$this->sendEmail($shipping);
            

            if($shipping->reseller_id){
                
                $to_name = User::find($shipping->reseller_id)->name;
                $to_email = User::find($shipping->reseller_id)->email;
                $recipient = User::find($shipping->client_id);
                if($this->isEmail($to_email)){
                    $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking, "clientName" => $recipient->name];
        
                    \Mail::send("emails.resellerNotification", $data, function($message) use ($to_name, $to_email, $shipping, $status) {
                        
                        $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." en ".$status->name."!");
                        $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
            
                    });
                }

            }

            if($shipping->recipient_id != null){
                $recipient = Recipient::find($shipping->recipient_id);
                $to_name = $recipient->name;
                $to_email = $recipient->email;
            }else if($shipping->client_id != null){
                $recipient = User::find($shipping->client_id);
                $to_name = $recipient->name;
                $to_email = $recipient->email;
            }
            
            if($this->isEmail($to_email)){

                $status = ShippingStatus::find($shipping->shipping_status_id);
    
                $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];
        
                \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping, $status) {
        
                    $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." en ".$status->name."!");
                    $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
        
                });

            }
            

            return response()->json(["success" => true, "msg" => "Envío Actualizado exitosamente"]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            if(\Auth::user()->role_id < 3){
                
                $shippings = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->orWhereHas('shippingStatus', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                })
                ->orWhereHas('client', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                    $q->orWhere('lastname', "like", "%".$request->search."%");
                })
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->orWhereHas('shippingStatus', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                })
                ->orWhereHas('client', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                    $q->orWhere('lastname', "like", "%".$request->search."%");
                })
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();
            
            }else if(\Auth::user()->role_id == 3){


                $shippings = Shipping::where("reseller_id", \Auth::user()->id)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->orWhereHas('shippingStatus', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                })
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("reseller_id", \Auth::user()->id)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->orWhereHas('shippingStatus', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                })
                ->with(['client' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }
            else if(\Auth::user()->role_id == 4){

                $shippings = Shipping::where("client_id", \Auth::user()->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->orWhereHas('shippingStatus', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                })
                ->orWhereHas('client', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                    $q->orWhere('lastname', "like", "%".$request->search."%");
                })
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("client_id", \Auth::user()->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->orWhereHas('shippingStatus', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                })
                ->orWhereHas('client', function($q) use($request){
                    $q->where('name', "like", "%".$request->search."%");
                    $q->orWhere('lastname', "like", "%".$request->search."%");
                })
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

            if(\Auth::user()->role_id < 3){

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();

                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }
            else if(\Auth::user()->role_id == 3){

                $shippings = Shipping::where("reseller_id", \Auth::user()->id)->with("recipient", "box", "shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("reseller_id", \Auth::user()->id)->with("recipient", "box", "shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }
            else if(\Auth::user()->role_id == 4){

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->orderBy("id", "desc")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->where("client_id", \Auth::user()->id)->get();

                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus", "shippingGuideShipping", "shippingGuideShipping.shippingGuide")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(["client" => function($q){
                    $q->withTrashed();
                }])
                ->where("client_id", \Auth::user()->id)->count();

            }
            
            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function show($id){
    
        $shipping = Shipping::where("id", $id)
                    ->with(['box' => function ($q) {
                        $q->withTrashed();
                    }])
                    ->with(['reseller' => function ($q) {
                        $q->withTrashed();
                    }])
                    ->with(['recipient' => function ($q) {
                        $q->withTrashed();
                    }])
                    ->with(['client' => function($q){
                        $q->withTrashed();
                    }])
                    ->with('shippingProducts')
                    ->first();
        if(!$shipping){
            abort(404);
        }

        return view("shippings.show", ["shipping" => $shipping]);

    }

    function getAllStatuses(){

        $statuses = ShippingStatus::all();
        return response()->json(["statuses" => $statuses]);

    }

    function showQrOptions($id){

        return view("qrOptions", ["id" => $id]);

    }

    function downloadQR($id, $label, $bill, $anonymous){

        $shipping = Shipping::where("id", $id)->with(['box' => function ($q) {
            $q->withTrashed();
        }])
        ->with(['recipient' => function ($q) {
            $q->withTrashed();
        }])->first();
        $data = "https://api.qrserver.com/v1/create-qr-code/?data=".url('/tracking').'?tracking='.$shipping->tracking."&amp;size=100x100";

        $pdf = PDF::loadView('pdf.qr', ["data" => $data, "shipping" => $shipping, "label" => $label, "bill" => $bill, "anonymous" => $anonymous]);
        //$pdf->setPaper([0, 0, 288, 430.87], 'portrait');
        $pdf->setPaper([0, 0, 288, 430.87], 'portrait');
        return $pdf->stream('qr'.$shipping->tracking.'.pdf');

    }

    function fetchByRecipient($recipient, $page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus")->where("recipient_id", $recipient)->orderBy("id", "desc")->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->get();
            $shippingsCount = Shipping::where("recipient_id", $recipient)->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->count();

            return response()->json(["success" => true, "shippings" => $shippings, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function exportExcel($start_date, $end_date){

        try{

            /*$shippings = Shipping::whereDate('created_at', '>=', $start_date)->whereDate("created_at", '<=', $end_date)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->limit(10)->get();

            dd($shippings);*/

            return Excel::download((new ShippingsExport)->forFromDate($start_date)->forToDate($end_date), uniqid().'envios'.$start_date.'-'.$end_date.'.xlsx');
    

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function exportPDF($start_date, $end_date){

        try{

            $shippings = Shipping::whereDate('created_at', '>=', $start_date)->whereDate("created_at", '<=', $end_date)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->get();

            $pdf = PDF::loadView('pdf.shippings', ["shippings" => $shippings]);
            return $pdf->stream();

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function shippingsPending(){

        return view("shippings.pending");

    }

    function massUpdate(Request $request){

        foreach($request->selectedShippings as $selectedShipping){

            $shipping = Shipping::find($selectedShipping["id"]);

            if($shipping->is_finished == 1){
                $shipping->shipping_status_id = $request->status;
                $shipping->update();

                $this->storeShippingHistory($shipping["id"], $request->status);

                SendUpdateEmail::dispatch($shipping["id"]);
            }

        }

        return response()->json(["success" => true, "msg" => "Envío por lote realizado"]);
    }

    function receiptPdf($id){

        $shipping = Shipping::where("id", $id)->with(['box' => function ($q) {
            $q->withTrashed();
        }])
        ->with(['recipient' => function ($q) {
            $q->withTrashed();
        }])->first();
        $data = "https://api.qrserver.com/v1/create-qr-code/?data=".url('/tracking').'?tracking='.$shipping->tracking."&amp;size=100x100";

        $pdf = PDF::loadView('pdf.receipt', ["data" => $data, "shipping" => $shipping]);
        return $pdf->stream('receipt'.$shipping->tracking.'.pdf');

    }

    function process(ShippingProcessRequest $request){

        try{

            $shipping = Shipping::find($request->shippingId);
            $shipping->box_id = $request->packageId;
            $shipping->pieces = $request->pieces;
            $shipping->length = $request->length;
            $shipping->height = $request->height;
            $shipping->weight = $request->weight;
            $shipping->width = $request->width;
            $shipping->reseller_id = $request->resellerId;
            $shipping->description = str_replace("'", "", $request->description);
            $shipping->address = str_replace("'", "", $request->address);
            $shipping->is_finished = 1;
            $shipping->shipped_at = Carbon::now();
            $shipping->shipping_status_id = 1;
            $shipping->update();

            $shipping->warehouse_number = "WRI".str_pad($shipping->id, 10, "0", STR_PAD_LEFT);
            $shipping->update();
    
            $this->storeShippingHistory($shipping->id, 1);

            $client = User::where("id", $shipping->client_id)->first();
            $to_name = $client->name;
            $to_email = $client->email;
            
            $status = ShippingStatus::find($shipping->shipping_status_id);

            $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];
    
            \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping) {
    
                $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." actualizado!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            return response()->json(["success" => true, "msg" => "Paquete procesado exitosamente"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function delete(Request $request){

        try{

            $shipping = Shipping::find($request->id);
            $shipping->delete();

            return response()->json(["success" => true, "msg" => "Shipping eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);

        }

    }

}
