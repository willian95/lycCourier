<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RecipientStoreRequest;
use App\Http\Requests\RecipientUpdateRequest;
use App\User;
use App\Shipping;
use Carbon\Carbon;
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

            $recipient = new User;
            $recipient->name = $request->name;
            $recipient->email  = $request->email;
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

    function update(RecipientUpdateRequest $request){

        try{

            if(User::where("email", $request->email)->where("id", "<>", $request->id)->count() <= 0){

                $recipient = User::find($request->id);
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

            $recipients = User::where("role_id", 4)->skip($skip)->take($dataAmount)->get();
            $recipientsCount = User::where("role_id", 4)->count();

            return response()->json(["success" => true, "recipients" => $recipients, "recipientsCount" => $recipientsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Error en el servidor"]);

        }
    }

    function erase(Request $request){
        try{

            $recipient = User::find($request->id);
            $recipient->email = $recipient->email.uniqid();
            $recipient->update();
            $recipient->delete();
            
            return response()->json(["success" => true, "msg" => "Destinatario eliminado"]);

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

    function searchList(Request $request){
        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            $recipients = User::where("name", "like", "%".$request->search."%")->orWhere("email", "like", "%".$request->search."%")->take($dataAmount)->skip($skip)->where("role_id", 4)->get();
            $recipientsCount = User::where("name", "like", "%".$request->search."%")->orWhere("email", "like", "%".$request->search."%")->where("role_id", 4)->count();

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

            $shippings = Shipping::skip($skip)->take($dataAmount)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['client' => function ($q) {
                $q->withTrashed();
            }])->with("shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->orderBy("id", "desc")->where("client_id", $recipient)->get();
            
            $shippingsCount = Shipping::with("shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->where("client_id", $recipient)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['client' => function ($q) {
                $q->withTrashed();
            }])->count();

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
