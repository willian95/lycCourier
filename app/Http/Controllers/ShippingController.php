<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShippingStoreRequest;
use App\Http\Requests\ShippingUpdateRequest;
use App\Http\Requests\ShippingUpdateInfoRequest;
use App\Http\Requests\ShippingPendingUpdateRequest;
use App\Traits\StoreShippingHistory;
use App\Traits\SendEmail;
use App\Shipping;
use App\ShippingStatus;
use App\Recipient;
use PDF;
use Carbon\Carbon;
use App\Jobs\SendUpdateEmail;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShippingsExport;

class ShippingController extends Controller
{
    use StoreShippingHistory;
    use SendEmail;
    
    function index(){
        return view("shippings.list");
    }

    function create(){
        return view("shippings.create.index");
    }

    function store(ShippingStoreRequest $request){

        try{

            $shipping = new Shipping;
            $shipping->tracking = $request->tracking;
            $shipping->recipient_id = $request->recipientId;
            $shipping->box_id = $request->packageId;
            $shipping->pieces = $request->pieces;
            $shipping->length = $request->length;
            $shipping->height = $request->height;
            $shipping->weight = $request->weight;
            $shipping->width = $request->width;
            $shipping->reseller_id = $request->resellerId;
            $shipping->shipping_status_id = 1;
            $shipping->description = $request->description;
            $shipping->is_finished = 1;
            $shipping->shipped_at = Carbon::now();
            $shipping->address = $request->address;
            $shipping->save();

            $shipping->warehouse_number = "WRI".str_pad($shipping->id, 10, "0", STR_PAD_LEFT);
            $shipping->update();
    
            $this->storeShippingHistory($shipping->id, 1);
            //$this->sendEmail($shipping);
            $recipient = Recipient::find($shipping->recipient_id);
            $to_name = $recipient->name;
            $to_email = $recipient->email;
            
            $status = ShippingStatus::find($shipping->shipping_status_id);

            $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];
    
            \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping) {
    
                $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." creado!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });
            
            return response()->json(["success" => true, "msg" => "Envío realizado exitosamente"]);
            
        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function updateInfo(ShippingUpdateInfoRequest $request){

        try{

            if(Shipping::where("tracking", $request->tracking)->where("id", "<>", $request->shippingId)->count() == 0){
                $shipping = Shipping::find($request->shippingId);
                $shipping->tracking = $request->tracking;
                $shipping->recipient_id = $request->recipientId;
                $shipping->box_id = $request->packageId;
                $shipping->pieces = $request->pieces;
                $shipping->length = $request->length;
                $shipping->height = $request->height;
                $shipping->weight = $request->weight;
                $shipping->width = $request->width;
                $shipping->reseller_id = $request->resellerId;
                $shipping->description = $request->description;
                $shipping->address = $request->address;
                $shipping->update();

                $this->storeShippingHistory($shipping->id, $shipping->shipping_status_id);

            }else{
                return response()->json(["success" => false, "msg" => "Este tracking ya lo posee otro envío"]);
            }
            
            return response()->json(["success" => true, "msg" => "Envío actualizado exitosamente"]);
            
        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }
        
    }

    function update(ShippingUpdateRequest $request){

        try{

            $shipping = Shipping::find($request->id);
            $shipping->shipping_status_id = $request->status;
            $shipping->update();

            $this->storeShippingHistory($shipping->id, $request->status);
            //$this->sendEmail($shipping);
            $recipient = Recipient::find($shipping->recipient_id);
            $to_name = $recipient->name;
            $to_email = $recipient->email;
            
            $status = ShippingStatus::find($shipping->shipping_status_id);
    
            $data = ["name" => $to_name, "status" => $status->name, "tracking" => $shipping->tracking];
    
            \Mail::send("emails.notification", $data, function($message) use ($to_name, $to_email, $shipping, $status) {
    
                $message->to($to_email, $to_name)->subject("¡Paquete ".$shipping->tracking." en ".$status->name."!");
                $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
    
            });

            return response()->json(["success" => true, "msg" => "Envío Actualizado exitosamente"]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function search(Request $request){

        try{

            $dataAmount = 20;
            $skip = ($request->page - 1) * $dataAmount;

            if(\Auth::user()->role_id < 3){
                
                $shippings = Shipping::where("is_finished", 1)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
    
                $shippingsCount = Shipping::where("is_finished", 1)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();
            
            }else{

                $shippings = Shipping::where("reseller_id", \Auth::user()->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->take($dataAmount)->skip($skip)->orderBy("id", "desc")
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->where("reseller_id", \Auth::user()->id)->get();
    
                $shippingsCount = Shipping::where("reseller_id", \Auth::user()->id)->where("tracking", "like", '%'.$request->search.'%')->orWhere("warehouse_number", "like", '%'.$request->search.'%')->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->count();

            }

            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);


        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function fetch($page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            if(\Auth::user()->role_id < 3){

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->orderBy("id", "desc")->where("is_finished", 1)
                ->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->get();
                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->where("is_finished", 1)->count();

            }else{

                $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->orderBy("id", "desc")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->where("reseller_id", \Auth::user()->id)->get();

                $shippingsCount = Shipping::with("recipient", "box", "shippingStatus", "shippingHistories", "shippingHistories.user", "shippingHistories.shippingStatus")->with(['box' => function ($q) {
                    $q->withTrashed();
                }])
                ->with(['recipient' => function ($q) {
                    $q->withTrashed();
                }])->where("reseller_id", \Auth::user()->id)->count();

            }
            
            return response()->json(["success" => true, "shippings" => $shippings, "shippingsCount" => $shippingsCount, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }
    function show($tracking){
    
        $shipping = Shipping::where("tracking", $tracking)
                    ->with(['box' => function ($q) {
                        $q->withTrashed();
                    }])
                    ->with(['reseller' => function ($q) {
                        $q->withTrashed();
                    }])
                    ->with(['recipient' => function ($q) {
                        $q->withTrashed();
                    }])
                    ->first();

        return view("shippings.show", ["shipping" => $shipping]);

    }

    function getAllStatuses(){

        $statuses = ShippingStatus::all();
        return response()->json(["statuses" => $statuses]);

    }

    function downloadQR($id){

        $shipping = Shipping::where("id", $id)->with(['box' => function ($q) {
            $q->withTrashed();
        }])
        ->with(['recipient' => function ($q) {
            $q->withTrashed();
        }])->first();
        $data = "https://api.qrserver.com/v1/create-qr-code/?data=".url('/tracking').'?tracking='.$shipping->tracking."&amp;size=100x100";

        $pdf = PDF::loadView('pdf.qr', ["data" => $data, "shipping" => $shipping]);
        //$pdf->setPaper([0, 0, 288, 430.87], 'portrait');
        $pdf->setPaper([0, 0, 288, 430.87], 'portrait');
        return $pdf->stream('qr'.$shipping->tracking.'.pdf');

    }

    function fetchByRecipient($recipient, $page = 1){

        try{

            $dataAmount = 20;
            $skip = ($page - 1) * $dataAmount;

            $shippings = Shipping::skip($skip)->take($dataAmount)->with("recipient", "box", "shippingStatus")->where("recipient_id", $recipient)->orderBy("id", "desc")->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->get();
            $shippingsCount = Shipping::where("recipient_id", $recipient)->with("recipient", "box", "shippingStatus")->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->count();

            return response()->json(["success" => true, "shippings" => $shippings, "dataAmount" => $dataAmount]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "err" => $e->getMessage(), "ln" => $e->getLine(), "msg" => "Hubo un problema"]);
        }

    }

    function exportExcel($start_date, $end_date){

        try{

            return Excel::download((new ShippingsExport)->forFromDate($start_date)->forToDate($end_date), uniqid().'envios'.$start_date.'-'.$end_date.'.xlsx');
    

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function exportPDF($start_date, $end_date){

        try{

            $shippings = Shipping::whereDate('created_at', '>=', $start_date)->whereDate("created_at", '<=', $end_date)->with(['box' => function ($q) {
                $q->withTrashed();
            }])
            ->with(['recipient' => function ($q) {
                $q->withTrashed();
            }])->get();

            $pdf = PDF::loadView('pdf.shippings', ["shippings" => $shippings]);
            return $pdf->stream();

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }

    }

    function shippingsPending(){

        return view("shippings.pending");

    }

    function massUpdate(Request $request){

        foreach($request->selectedShippings as $selectedShipping){

            $shipping = Shipping::find($selectedShipping["id"]);
            $shipping->shipping_status_id = $request->status;
            $shipping->update();

            $this->storeShippingHistory($shipping["id"], $request->status);

            SendUpdateEmail::dispatch($shipping["id"]);

        }

        return response()->json(["success" => true, "msg" => "Envío por lote realizado"]);
    }

    function receiptPdf($id){

        $shipping = Shipping::where("id", $id)->with(['box' => function ($q) {
            $q->withTrashed();
        }])
        ->with(['recipient' => function ($q) {
            $q->withTrashed();
        }])->first();
        $data = "https://api.qrserver.com/v1/create-qr-code/?data=".url('/tracking').'?tracking='.$shipping->tracking."&amp;size=100x100";

        $pdf = PDF::loadView('pdf.receipt', ["data" => $data, "shipping" => $shipping]);
        return $pdf->stream('receipt'.$shipping->tracking.'.pdf');

    }

}
