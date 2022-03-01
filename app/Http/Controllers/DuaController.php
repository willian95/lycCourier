<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DUAStoreRequest;
use App\DuaNew;
use Illuminate\Support\Facades\Log;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DuaImport;

class DuaController extends Controller
{
    
    function create(){

        return view("dua.create.index");

    }

    function update(Request $request){

        try{

            $dua = DuaNew::find($request->id);
            $dua->hawb = $request->hawb;
            $dua->esser = $request->esser;
            $dua->client = $request->client;
            $dua->volante = $request->volante;
            $dua->tc = $request->tc;
            $dua->real_date = $request->arrivalDate;
            $dua->dua = $request->dua;
            $dua->manifest = $request->manifest;
            $dua->awb = $request->awb;
            $dua->pieces = $request->pieces;
            $dua->weight = $request->weight;
            $dua->update();

            return response()->json(["success" => true, "message" => "Dua actualizado"], 200);

        }catch(\Exception $e){

            Log::error($e);
            return response()->json(["success" => false, "message" => "Ha ocurrido un problema"], 200);

        }

    }

    function fetch(){

        $duas = DuaNew::with("shippingGuide.shippingGuideShipping.shipping")->orderBy("hawb", "desc")->paginate(20);
        return response()->json($duas);
    }

    function uploadFile(Request $request){

        $path = Storage::disk('local')->put("/", $request->file('file'));
        Excel::import(new DuaImport, $path);

        return redirect()->back();

    }

    function show(Request $request){

        $dua = DuaNew::where("dua", $request->dua)->with("shippingGuide.shippingGuideShipping.shipping")->first();
        return view("dua", ["dua" => $dua]);

    }  

    function pdf(Request $request){

        $dua = DuaNew::where("dua", $request->dua)->with("shippingGuide.shippingGuideShipping.shipping")->first();
        $data = "https://api.qrserver.com/v1/create-qr-code/?data=".url('/dua/search').'?dua='.$dua->dua."&amp;size=100x100";

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.dua', ["data" => $data, "dua" => $dua]);
        //$pdf->setPaper([0, 0, 288, 430.87], 'portrait');
        $pdf->setPaper([0, 0, 288, 430.87], 'landscape');
        return $pdf->stream('qr'.$dua->dua.'.pdf');

    }

}
