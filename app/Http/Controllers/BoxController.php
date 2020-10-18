<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BoxStoreRequest;
use App\Http\Requests\BoxUpdateRequest;
use App\Box;

class BoxController extends Controller
{
    
    function index(){
        return view("boxes.index");
    }

    function store(BoxStoreRequest $request){

        try{

            $box = new Box;
            $box->name = $request->name;
            $box->save();
            
            return response()->json(["success" => true, "msg" => "Tipo de paquete registrado", "box" => $box]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function update(BoxUpdateRequest $request){

        try{

            if(Box::where("name", $request->name)->where("id", "<>", $request->id)->count() <= 0){

                $box = Box::find($request->id);
                $box->name = $request->name;
                $box->update();

            }else{  

                return response()->json(["success" => false, "msg" => "Este nombre lo posee otro tipo de paquete"]);

            }
            
            return response()->json(["success" => true, "msg" => "Tipo de paquete actualizado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function fetch($page = 1){
        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $boxes = Box::skip($skip)->take($dataAmount)->get();
            $boxesCount = Box::count();

            return response()->json(["success" => true, "boxes" => $boxes, "boxesCount" => $boxesCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }
    }

    function erase(Request $request){
        try{

            $box = Box::find($request->id);
            $box->delete();
            
            return response()->json(["success" => true, "msg" => "Tipo de paquete eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
    }

    function search(Request $request){
        try{

            $boxes = Box::where("name", "like", "%".$request->search."%")->take(20)->get();
            return response()->json(["success" => true, "boxes" => $boxes]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
    }

    


}
