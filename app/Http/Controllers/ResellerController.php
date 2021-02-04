<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResellerRecipientStore;
use App\Http\Requests\ResellerRecipientUpdate;
use App\User;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class ResellerController extends Controller
{
    function fetch(){

        try{    

            $users = User::where("role_id", 3)->get();
            return response()->json(["resellers" => $users]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function createRecipient(){

        return view("recipients.reseller.resellerCreateRecipient");

    }

    function editRecipient($id){

        $user = User::find($id);
        return view("recipients.reseller.resellerEditRecipient", ["user" => $user]);

    }

    function updateRecipient(ResellerRecipientUpdate $request){

        try{

            if(User::where("email", $request->email)->where("id", "<>", $request->id)->count() > 0){
                return response()->json(["success" => false, "msg" => "Este email ya está en uso"]);
            }

            $fileName = $this->storeImage("image", $request);

            $fileNameBack = $this->storeImage("imageBack", $request);

            $user = User::find($request->id);
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->dni = $request->dni;
            $user->email = $request->email;
            $user->role_id = 4;
            $user->address = $request->address;
            $user->department_id = $request->department;
            $user->district_id = $request->district;
            $user->province_id = $request->province;
            if($request->get('image') != null){
                $user->dni_picture = url('/img/clients')."/".$fileName;
            }
            if($request->get('imageBack') != null){
                $user->dni_picture_back = url('/img/clients')."/".$fileNameBack;
            }
            if($request->has("password") && $request->password != ""){
                $user->password = bcrypt($request->password);
            }
            $user->update();

            return response()->json(["success" => true, "msg" => "Destinatario actualizado"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function storeRecipient(ResellerRecipientStore $request){

        try{

            $fileName = $this->storeImage("image", $request);

            $fileNameBack = $this->storeImage("imageBack", $request);

            $user = new User;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->dni = $request->dni;
            $user->email = $request->email;
            $user->role_id = 4;
            $user->address = $request->address;
            $user->department_id = $request->department;
            $user->district_id = $request->district;
            $user->province_id = $request->province;
            if($request->get('image') != null){
                $user->dni_picture = url('/img/clients')."/".$fileName;
            }
            if($request->get('imageBack') != null){
                $user->dni_picture_back = url('/img/clients')."/".$fileNameBack;
            }
            if($request->has("password") && $request->password != ""){
                $user->password = bcrypt($request->password);
            }
            $user->reseller_id = \Auth::user()->id;
            $user->save();

            $this->sendEmail($user);

            return response()->json(["success" => true, "msg" => "Destinatario creado"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function storeImage($image, $request){

        try{
        
            $imageData = $request->get($image);

            if(strpos($imageData, "svg+xml") > 0){

                $data = explode( ',', $imageData);
                $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.'."svg";
                $ifp = fopen($fileName, 'wb' );
                fwrite($ifp, base64_decode( $data[1] ) );
                rename($fileName, 'img/clients/'.$fileName);

            }else{

                $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                Image::make($request->get($image))->save(public_path('img/clients/').$fileName);
            }

            return $fileName;

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema con la imagen", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function sendEmail($user){

        $to_name = $user->name;
        $to_email = $user->email;

        $data = ["messageMail" => "Hola ".$user->name.", has sido invitado a LycCourier por ".\Auth::user()->name.". Has click en el siguiente enlace para validar tu cuenta", "registerHash" => $registerHash];

        \Mail::send("emails.register2", $data, function($message) use ($to_name, $to_email) {
    
            $message->to($to_email, $to_name)->subject("¡Valida tu correo!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });

    }

}
