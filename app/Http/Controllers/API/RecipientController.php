<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RecipientStoreRequest;
use Carbon\Carbon;
use App\User;

class RecipientController extends Controller
{

    function store(RecipientStoreRequest $request){

        try{

            $recipient = new User;
            $recipient->name = $request->name;
            $recipient->lastname = $request->lastname;
            if(isset($request->email)){
                $recipient->email  = $request->email;
            }else{
                $recipient->email  = uniqid();
            }
            
            $recipient->password = bcrypt(uniqid());
            $recipient->phone = $request->phone;
            $recipient->email_verified_at = Carbon::now();
            $recipient->address = $request->address;
            $recipient->role_id = 4;
            $recipient->save();
            
            return response()->json(["success" => true, "msg" => "Destinatario registrado", "recipient" => $recipient]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function search(Request $request){
        try{

            $recipients = User::where("name", "like", "%".$request->search."%")->orWhere("email", "like", "%".$request->search."%")->take(20)->where("role_id", 4)->get();
            return response()->json(["success" => true, "recipients" => $recipients]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
    }
}
