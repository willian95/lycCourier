<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = "districts";

    public function client(){
        return $this->hasMany(User::class);
    }

}
