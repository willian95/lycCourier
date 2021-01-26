<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\ClientShippingRequest;
use App\Http\Requests\ClientShippingUpdateRequest;
use App\Shipping;
use App\ShippingProduct;
use Carbon\Carbon;
use App\AdminMail;
use Intervention\Image\Facades\Image;
use Auth;

class ClientShippingController extends Controller
{
    
    function store(ClientShippingRequest $request){

        try{

            $client = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

            $shipping = new Shipping;
            $shipping->tracking = $request->tracking;
            $shipping->client_id = $client->id;
            $shipping->address = str_replace("'", "", $request->address);
            $shipping->save();

            $this->storeProducts($request, $shipping);
            $this->sendAdminEmails($client, $request, $shipping);
            
            return response()->json(["success" => true, "msg" => "Haz creado un envío, aguarde su aprobación"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function storeProducts($request, $shipping){

        foreach($request->products as $product){
            
            $imageInfo = $this->storeImage($product, $product);
            
            $shippingProduct = new ShippingProduct;
            $shippingProduct->name = str_replace("'", "", $product["name"]);
            $shippingProduct->price = $product["price"];
            $shippingProduct->shipping_id = $shipping->id;
            if($product["image"] != null){
                $shippingProduct->image = url('/img/bills/')."/".$imageInfo["fileName"];
                $shippingProduct->file_type = $imageInfo["fileType"];
            }
            $shippingProduct->save();

        }
    }

    function updateProducts($request, $shipping){

        foreach($request->products as $product){
            
            $imageInfo = $this->storeImage($product, $product);
            
            $shippingProduct = ShippingProduct::where("id", $product["id"])->first();
            $shippingProduct->name = str_replace("'", "", $product["name"]);
            $shippingProduct->price = $product["price"];
            $shippingProduct->shipping_id = $shipping->id;
            if($product["image"] != null){
                $shippingProduct->image = url('/img/bills/')."/".$imageInfo["fileName"];
                $shippingProduct->file_type = $imageInfo["fileType"];
            }
            $shippingProduct->update();

        }
    }


    public function storeImage($product){

        $fileType="image";
        $fileName="";

        if($product["image"] != null){
            try{
    
                $imageData = $product["image"];

                if(strpos($imageData, "svg+xml") > 0){
                    $fileType = "image";
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

                return ["fileType" => $fileType, "fileName" => $fileName];
    
            }catch(\Exception $e){
    
                return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);
    
            }

            

        }
    }

    function  sendAdminEmails($client, $request, $shipping){

        $data = ["messageTitle" => "Nuevo envío creado", "messageMail" => "El cliente ".$client->name." ".$client->lastname." ha creado un nuevo envío con el tracking: <strong>".$request->tracking."</strong>", "shippingId" => $shipping->tracking];

        foreach(AdminMail::all() as $admin){

            $to_email = $admin->email;

            \Mail::send("emails.adminNotification", $data, function($message) use ($to_email) {

                $message->to($to_email)->subject("¡Un usuario ha creado un envío!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

        }
    }

    function fetch($page){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;
            $client = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

            $shippings = Shipping::skip($skip)->take($dataAmount)->where("client_id", $client->id)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
            }])
            ->with("shippingStatus", "shippingHistories", "shippingHistories.shippingStatus", "shippingProducts")->orderBy("id", "desc")->get();
            
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

    function update(ClientShippingUpdateRequest $request){

        try{

            $shipping = Shipping::where("id", $request->id)->first();
            $client = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

            if(Shipping::where("tracking", $request->tracking)->where("client_id", "<>", $client->id)->count() == 0){

                $shipping->tracking = $request->tracking;
                $shipping->address = str_replace("'", "", $request->address);
                $shipping->update();

                $this->checkAndDeleteShippingProducts($shipping);

                foreach($request->products as $product){

                    if(isset($product["id"])){
                        
                        $this->updateProducts($request, $shipping);

                    }else{

                        $this->storeProducts($request, $shipping);

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

    function checkAndDeleteShippingProducts($shipping){
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
    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $client = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();
            $skip = ($request->page - 1) * $dataAmount;

            $shippings = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->skip($skip)->take($dataAmount)->where("client_id", $client->id)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(["client" => function($q){
                $q->withTrashed();
            }])
            ->with("shippingStatus", "shippingHistories", "shippingProducts")->orderBy("id", "desc")->get();

            $shippingsCount = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->where("client_id", $client->id)->with(['box' => function ($q) {
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

}
