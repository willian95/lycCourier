<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingGuideShipping extends Model
{
    
    public function shipping(){
        return $this->belongsTo(Shipping::class);
    }

    public function shippingGuide(){
        return $this->belongsTo(ShippingGuide::class);
    }

}
