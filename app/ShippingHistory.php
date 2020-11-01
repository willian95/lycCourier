<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingHistory extends Model
{
    use SoftDeletes;

    public function shippingHistory(){
        return $this->belongsTo(ShippingHistory::class);
    }

    public function shippingStatus(){
        return $this->belongsTo(ShippingStatus::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
