<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecipientStoreRequest;
use App\Http\Requests\RecipientUpdateRequest;
use App\Recipient;
use App\Shipping;

use PDF;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RecipientsExport;

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

    function searchList(Request $request){
        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $recipients = Recipient::where("name", "like", "%".$request->search."%")->orWhere("email", "like", "%".$request->search."%")->take($dataAmount)->skip($skip)->get();
            $recipientsCount = Recipient::where("name", "like", "%".$request->search."%")->orWhere("email", "like", "%".$request->search."%")->count();

            return response()->json(["success" => true, "recipients" => $recipients, "recipientsCount" => $recipientsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
    }

    function exportExcel(){

        try{

            return Excel::download(new RecipientsExport, uniqid().'destinatarios.xlsx');

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function exportPDF(){

        try{

            $pdf = PDF::loadView('pdf.recipients');
            return $pdf->stream();

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function shippingList($recipient){
        return view("recipients.shippingList", ["recipient" => $recipient]);
    }

    function shippingFetch($recipient, $page){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->has("recipient")->has("box")->orderBy("id", "desc")->where("recipient_id", $recipient)->get();
            $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories")->where("recipient_id", $recipient)->has("recipient")->has("box")->count();

            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }
    
    function searchShipping(Request $request){

        try{

            $shippings = Shipping::where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->where("recipient_id", $request->recipient)->take(40)->orderBy("id", "desc")->get();

            return response()->json(["success" => true, "shippings" => $shippings]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }


}
