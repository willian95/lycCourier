<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Str;
use App\User;

class RegisterController extends Controller
{
    
    function index(){
        return view("clients.register");
    }

    function register(RegisterRequest $request){

        try{

            $registerHash = Str::random(40);

            $user = new User;
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->dni = $request->dni;
            $user->address = $request->address;
            $user->role_id = 4;
            $user->password = bcrypt($request->password);
            $user->register_code = $registerHash;
            $user->save();

            $to_name = $user->name;
            $to_email = $user->email;
        
            $data = ["messageMail" => "Hola ".$user->name.", haz click en el siguiente enlace para validar tu cuenta", "registerHash" => $registerHash];
    
            \Mail::send("emails.register", $data, function($message) use ($to_name, $to_email) {
    
                $message->to($to_email, $to_name)->subject("¡Valida tu correo!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            return response()->json(["success" => true, "msg" => "Te has registrado exitosamente, hemos enviado un mensaje a tu correo"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function verify($registerHash){

        try{

            $user = User::where("register_code", $registerHash)->firstOrFail();
            $user->register_hash = null;
            $user->email_verified_at = Carbon::now();
            $user->update();
            
            return redirect()->to('/')->with('alert', 'Haz validado tu cuenta, puedes ingresar a la plataforma');

        }catch(\Exception $e){
            abort(403);
        }

    }

}
