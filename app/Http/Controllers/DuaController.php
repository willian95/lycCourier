<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DUAStoreRequest;
use App\Dua;
use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\PDF;

class DuaController extends Controller
{
    
    function create(){

        return view("dua.create.index");

    }

    function store(DUAStoreRequest $request){

        try{

            $dua = new Dua;
            $dua->hawb = $request->hawb;
            $dua->esser = $request->esser;
            $dua->client = $request->client;
            $dua->volante = $request->volante;
            $dua->tc = $request->tc;
            $dua->arrivalDate = $request->arrivalDate;
            $dua->dua = $request->dua;
            $dua->manifest = $request->manifest;
            $dua->awb = $request->awb;
            $dua->pieces = $request->pieces;
            $dua->weight = $request->weight;
            $dua->shipping_guide_id = $request->shipping_guide_id;
            $dua->save();

            return response()->json(["success" => true, "message" => "Dua creada"], 200);

        }catch(\Exception $e){

            Log::error($e);
            return response()->json(["success" => false, "message" => "Ha ocurrido un problema"], 200);


        }

    }

    function show(Request $request){

        $dua = Dua::where("dua", $request->dua)->with("shippingGuide.shippingGuideShipping.shipping")->first();
        return view("dua", ["dua" => $dua]);

    }  

    function pdf(Request $request){

        $dua = Dua::where("dua", $request->dua)->with("shippingGuide.shippingGuideShipping.shipping")->first();
        $data = "https://api.qrserver.com/v1/create-qr-code/?data=".url('/dua/search').'?dua='.$dua->dua."&amp;size=100x100";

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.dua', ["data" => $data, "dua" => $dua]);
        //$pdf->setPaper([0, 0, 288, 430.87], 'portrait');
        $pdf->setPaper([0, 0, 288, 430.87], 'landscape');
        return $pdf->stream('qr'.$dua->dua.'.pdf');

    }

}
