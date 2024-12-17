<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MasterGarage extends Model
{

    protected $table = 'master_garage';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'type_steel' => 'array',
        'thickness_steel' => 'array',
        'type_sheet' => 'array',
      ];
}
