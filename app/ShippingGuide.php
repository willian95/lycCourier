<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingGuide extends Model
{

    use SoftDeletes;

    public function shippingGuideShipping(){
        return $this->hasMany(ShippingGuideShipping::class);
    }

    public function dua(){
        return $this->hasOne(Dua::class);
    }

}
