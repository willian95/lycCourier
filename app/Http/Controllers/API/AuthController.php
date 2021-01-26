<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PasswordRestoreRequest;
use Illuminate\Support\Str;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\LoginRequest;
use Auth;

class AuthController extends Controller
{
    
    function login(LoginRequest $request){

        try{

            $user = User::where("email", $request->email)->first();
            if($user){

                if($user->email_verified_at == null){

                    return response()->json(["success" => false, "msg" => "Aún no has validado tu correo"]);

                }else{
                    $credentials = $request->only('email', 'password');
                    if (! $token = JWTAuth::attempt($credentials)) {
                        return response()->json(['error' => 'invalid_credentials'], 400);
                    }else{
                        return response()->json(["success" => true, "msg" => "Haz ingresado", "token" => $token, "user" => $user]);   
                    }         

                }
                    
                return response()->json(["success" => false, "msg" => "Contraseña inválida"]);
                

            }else{
                return response()->json(["success" => false, "msg" => "Usuario no encontrado"]);
            }

        }catch(JWTException $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function me(){

        try{

            $user=Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();
            return response()->json(["user" => $user]);

        }catch(JWTException $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

    function verifyEmail(PasswordRestoreRequest $request){

        try{

            $recoveryHash = Str::random(40);
            $user = User::where("email", $request->email)->first();
            $user->recovery_hash = $recoveryHash;
            $user->update();

            $to_name = $user->name;
            $to_email = $user->email;
        
            $data = ["messageMail" => "Hola ".$user->name.", haz click en el siguiente enlace para reestablecer tu contraseña", "registerHash" => $recoveryHash];
    
            \Mail::send("emails.restorePass", $data, function($message) use ($to_name, $to_email) {
    
                $message->to($to_email, $to_name)->subject("Reestablece tu contraseña");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            return response()->json(["success" => true, "msg" => "Hemos enviado un correo de recuperación"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

}
