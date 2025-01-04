<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectGarageCustomer extends Model
{
    protected $table = 'project_garage_customer';

    protected $guarded = [
        'id'
    ];

    public function scopeFindId($query,$id)
    {
        return $query->where('id',$id);
    }
}
