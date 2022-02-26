<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dua extends Model
{
    public function shippingGuide(){
        return $this->belongsTo(ShippingGuide::class);
    }
}
