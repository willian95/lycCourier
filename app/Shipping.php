<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipping extends Model
{

    use SoftDeletes;

    protected $dates = ["shipped_at"];

    public function recipient(){
        return $this->belongsTo(Recipient::class);
    }

    public function box(){
        return $this->belongsTo(Box::class);
    }

    public function shippingStatus(){
        return $this->belongsTo(ShippingStatus::class);
    }

    public function shippingHistories(){
        return $this->hasMany(ShippingHistory::class);
    }

    public function reseller(){
        return $this->belongsTo(User::class, "reseller_id");
    }

    public function products(){
        return $this->hasMany(ShippingProduct::class);
    }

    public function client(){
        return $this->belongsTo(User::class, "client_id");
    }

    public function shippingProducts(){
        return $this->hasMany(ShippingProduct::class);
    }

}
