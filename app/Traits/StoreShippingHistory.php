<?php 
namespace App\Traits;

use App\ShippingHistory;
use App\Shipping;
use Auth;

trait StoreShippingHistory
{
    public function storeShippingHistory($shipping_id, $status_id)
    {

        $shipping = Shipping::where("id", $shipping_id)
        ->with(['box' => function ($q) {
            $q->withTrashed();
        }])
        ->with(['reseller' => function ($q) {
            $q->withTrashed();
        }])
        ->with(['recipient' => function ($q) {
            $q->withTrashed();
        }])->first();

        $description = "tracking: ".$shipping->tracking.", tipo de empaque: ".$shipping->box->name.", piezas: ".$shipping->pieces.", largo: ".$shipping->length.", alto: ".$shipping->height.", ancho: ".$shipping->width.", peso: ".$shipping->weight.", descripción: ".$shipping->description.", address: ".str_replace(",", "", $shipping->address);

        if($shipping->reseller){
            $description .= ", reseller: ".$shipping->reseller->name;
        }

        if($shipping->client){
            $description .= ", destinatario: ".$shipping->client->name;
        }

        if($shipping->recipient){
            $description .= ", destinatario: ".$shipping->recipient->name;
        }

        $auth = Auth::guard('api')->user() ? Auth::guard('api')->user() : Auth::user();

        $shippingHistory = new ShippingHistory;
        $shippingHistory->shipping_id = $shipping_id;
        $shippingHistory->shipping_status_id = $status_id;
        $shippingHistory->user_id = $auth->id;
        $shippingHistory->description = $description;
        $shippingHistory->save();
    }

}