<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingStatus extends Model
{

    use SoftDeletes;

    public function shippings(){
        return $this->hasMany(Shipping::class);
    }
    
}
