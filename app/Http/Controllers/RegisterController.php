<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;

class RegisterController extends Controller
{
    
    function index(){
        return view("clients.register");
    }

    function register(RegisterRequest $request){

        try{

            $validationResult = $this->validateResellerEmail($request);

            if($validationResult["success"] == false){
                return response()->json($validationResult);
            }

            $registerHash = Str::random(40);    

            $user = new User;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->address = $request->address;
            $user->role_id = 4;
            $user->password = bcrypt($request->password);
            $user->email_verified_at = Carbon::now();
            $user->register_code = $registerHash;

            if(isset($request->resellerEmail)){
                $user->reseller_id = User::where("email", $request->resellerEmail)->first()->id;
            }

            $user->save();

            $to_name = $user->name;
            $to_email = $user->email;
        
            $data = ["messageMail" => "Hola ".$user->name.", bienvenido a LycCourier", "registerHash" => $registerHash];
    
            \Mail::send("emails.register2", $data, function($message) use ($to_name, $to_email) {
    
                $message->to($to_email, $to_name)->subject("¡Bienvenido a lycCourier!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            return response()->json(["success" => true, "msg" => "Te has registrado exitosamente, hemos enviado un mensaje a tu correo"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

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

    function verify($registerHash){

        try{

            $user = User::where("register_code", $registerHash)->firstOrFail();
            $user->register_code = null;
            $user->email_verified_at = Carbon::now();
            $user->update();
            
            return redirect()->to('/')->with('alert', 'Haz validado tu cuenta, puedes ingresar a la plataforma');

        }catch(\Exception $e){
            
            abort(403);
        }

    }

}
