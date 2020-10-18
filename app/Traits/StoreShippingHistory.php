<?php 
namespace App\Traits;

use App\ShippingHistory;

trait StoreShippingHistory
{
    public function storeShippingHistory($shipping_id, $status_id)
    {
        $shippingHistory = new ShippingHistory;
        $shippingHistory->shipping_id = $shipping_id;
        $shippingHistory->shipping_status_id = $status_id;
        $shippingHistory->user_id = \Auth::user()->id;
        $shippingHistory->save();
    }

}