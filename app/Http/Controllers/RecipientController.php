<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecipientStoreRequest;
use App\Http\Requests\RecipientUpdateRequest;
use App\Recipient;

class RecipientController extends Controller
{
    function index(){
        return view("recipients.index");
    }

    function store(RecipientStoreRequest $request){

        try{

            $recipient = new Recipient;
            $recipient->name = $request->name;
            $recipient->email  = $request->email;
            $recipient->phone = $request->phone;
            $recipient->address = $request->address;
            $recipient->save();
            
            return response()->json(["success" => true, "msg" => "Destinatario registrado", "recipient" => $recipient]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function update(RecipientUpdateRequest $request){

        try{

            if(Recipient::where("email", $request->email)->where("id", "<>", $request->id)->count() <= 0){

                $recipient = Recipient::find($request->id);
                $recipient->name = $request->name;
                $recipient->email  = $request->email;
                $recipient->phone = $request->phone;
                $recipient->address = $request->address;
                $recipient->update();

            }else{  

                return response()->json(["success" => false, "msg" => "Este email lo posee otro destinatario"]);

            }
            
            return response()->json(["success" => true, "msg" => "Destinatario actualizado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function fetch($page = 1){
        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $recipients = Recipient::skip($skip)->take($dataAmount)->get();
            $recipientsCount = Recipient::count();

            return response()->json(["success" => true, "recipients" => $recipients, "recipientsCount" => $recipientsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }
    }

    function erase(Request $request){
        try{

            $recipient = Recipient::find($request->id);
            $recipient->delete();
            
            return response()->json(["success" => true, "msg" => "Destinatario eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
    }

    function search(Request $request){
        try{

            $recipients = Recipient::where("name", "like", "%".$request->search."%")->orWhere("email", "like", "%".$request->search."%")->take(20)->get();
            return response()->json(["success" => true, "recipients" => $recipients]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
    }

}
