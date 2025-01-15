<?php

namespace App;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [
        'id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->emp_code = IdGenerator::generate(['table' => $model->getTable(), 'length' => 11,'field' => 'emp_code', 'prefix' => 'E'.date('Ym'),'reset_on_prefix_change' => true]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
