<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminMailRequest;
use App\Http\Requests\AdminMailUpdateRequest;
use App\AdminMail;

class AdminMailController extends Controller
{
    
    function index(){
        return view("adminMails.index");
    }

    function store(AdminMailRequest $request){


        try{

            $adminMail = new AdminMail;
            $adminMail->email = $request->email;
            $adminMail->save();

            return response()->json(["success" => true, "msg" => "Mail administrativo creado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        
    }

    function fetch(){

        try{

            $mails = AdminMail::all();

            return response()->json(["success" => true, "mails" => $mails]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }

    }

    function update(AdminMailUpdateRequest $request){

        try{

            if(AdminMail::where('email', $request->email)->where('id', '<>', $request->id)->count() == 0){
                
                $mail = AdminMail::find($request->id);
                $mail->email = $request->email;
                $mail->update();

                return response()->json(["success" => true, "msg" => "Mail administrativo actualizado"]);
            
            }else{

                return response()->json(["success" => false, "msg" => "Este nombre ya existe"]);

            }

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        
    }

    function delete(Request $request){

        try{

            $mail = AdminMail::find($request->id);
            $mail->delete();

            return response()->json(["success" => true, "msg" => "Mail administrativo eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }

    }

}
