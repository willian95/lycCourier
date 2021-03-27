<?php

use Illuminate\Database\Seeder;
use App\Shipping;
use App\ShippingGuide;
use App\ShippingGuideShipping;

class ShippingGuideShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        foreach(ShippingGuide::all() as $shippingGuide){

            foreach(Shipping::where("shipping_guide_id", $shippingGuide->id)->get() as $shipping){

                $shippingGuideShipping = new ShippingGuideShipping;
                $shippingGuideShipping->shipping_guide_id = $shippingGuide->id;
                $shippingGuideShipping->shipping_id = $shipping->id;
                $shippingGuideShipping->save();

            }

        }

    }
}
