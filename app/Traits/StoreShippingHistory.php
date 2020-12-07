<?php 
namespace App\Traits;

use App\ShippingHistory;
use App\Shipping;

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

        $description = "tracking: ".$shipping->tracking.", destinatario: ".$shipping->recipient->name.", tipo de empaque: ".$shipping->box->name.", piezas: ".$shipping->pieces.", largo: ".$shipping->length.", alto: ".$shipping->height.", ancho: ".$shipping->width.", peso: ".$shipping->weight.", descripción: ".$shipping->description.", address: ".str_replace(",", "", $shipping->address);

        if($shipping->reseller){
            $description .= ", reseller: ".$shipping->reseller->name;
        }

        $shippingHistory = new ShippingHistory;
        $shippingHistory->shipping_id = $shipping_id;
        $shippingHistory->shipping_status_id = $status_id;
        $shippingHistory->user_id = \Auth::user()->id;
        $shippingHistory->description = $description;
        $shippingHistory->save();
    }

}