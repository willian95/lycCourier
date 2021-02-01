<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = "provinces";

    public function client(){
        return $this->hasMany(User::class);
    }

}
