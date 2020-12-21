<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingProduct extends Model
{
    public function shipping(){
        return $this->belongsTo(Shipping::class);
    }
}
