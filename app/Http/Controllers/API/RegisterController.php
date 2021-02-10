<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;

class RegisterController extends Controller
{
    
    function register(RegisterRequest $request){

        try{

            $validationResult = $this->validateResellerEmail($request);

            if($validationResult["success"] == false){
                return response()->json($validationResult);
            }

            $registerHash = $this->registerHash();

            $user = new User;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->role_id = 4;
            $user->password = bcrypt($request->password);
            $user->register_code = $registerHash;
            if(isset($request->resellerEmail)){
                $user->reseller_id = User::where("email", $request->resellerEmail)->first()->id;
            }
            $user->save();

            $this->sendEmail($user, $registerHash);

            return response()->json(["success" => true, "msg" => "Te has registrado exitosamente, hemos enviado un mensaje a tu correo"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function registerHash(){
        return Str::random(40);
    }

    function sendEmail($user, $registerHash){
        $to_name = $user->name;
        $to_email = $user->email;
    
        $data = ["messageMail" => "Hola ".$user->name.", haz click en el siguiente enlace para validar tu cuenta", "registerHash" => $registerHash];

        \Mail::send("emails.register2", $data, function($message) use ($to_name, $to_email) {

            $message->to($to_email, $to_name)->subject("¡Valida tu correo!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });
    }

    function validateResellerEmail($request){

        if(isset($request->resellerEmail)){

            $user = User::where("email", $request->resellerEmail)->first();

            if($user){

                if($user->role_id != 3){

                    return ["success" => false, "msg" => "Email ingresado no pertenece a un socio"];

                }

            }else{

                return ["success" => false, "msg" => "Socio no enconrado"];

            }

        }

        return ["success" => true];

    }

}
