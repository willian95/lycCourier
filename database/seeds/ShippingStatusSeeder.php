<?php

use Illuminate\Database\Seeder;
use App\ShippingStatus;

class ShippingStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        if(ShippingStatus::count() == 0){

            $status = new ShippingStatus;
            $status->id = 1;
            $status->name = "Miami";
            $status->save();

            /*$status = new ShippingStatus;
            $status->id = 2;
            $status->name = "Camino a Lima";
            $status->save();*/

            $status = new ShippingStatus;
            $status->id = 2;
            $status->name = "Transito";
            $status->save();

            $status = new ShippingStatus;
            $status->id = 3;
            $status->name = "Aduana (Lima)";
            $status->save();

            $status = new ShippingStatus;
            $status->id = 4;
            $status->name = "Delivery";
            $status->save();

        }

    }
}
