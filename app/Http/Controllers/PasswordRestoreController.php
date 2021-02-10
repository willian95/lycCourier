<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PasswordRestoreRequest;
use App\Http\Requests\PasswordChangeRequest;
use Illuminate\Support\Str;
use App\User;

class PasswordRestoreController extends Controller
{
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

    function index($recoveryHash){

        try{

            $user = User::where("recovery_hash", $recoveryHash)->firstOrFail();
            return view("passwordRestore", ["user" => $user]);

        }catch(\Exception $e){
            return response()->json(["err" => $e->getMessage()]);
        }

    }

    function change(PasswordChangeRequest $request){

        try{

            $user = User::where("recovery_hash", $request->recoveryHash)->firstOrFail();
            $user->password = bcrypt($request->password);
            $user->recovery_hash = null;
            $user->update();

            return response()->json(["success" => true, "msg" => "Excelente! Haz reestablecido tu contraseña"]);

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        }

    }

}
