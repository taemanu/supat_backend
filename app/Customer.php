<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    // protected $table = 'customers';

    protected $guarded = [
        'id'
    ];

    public function scopeFindId($query,$id)
    {
        return $query->where('id',$id);
    }
}
