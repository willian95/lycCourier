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

}
