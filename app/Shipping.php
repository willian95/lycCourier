<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    public function recipient(){
        return $this->belongsTo(Recipient::class);
    }

    public function box(){
        return $this->belongsTo(Box::class);
    }

    public function shippingStatus(){
        return $this->belongsTo(ShippingStatus::class);
    }
}
