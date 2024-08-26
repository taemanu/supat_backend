<?php

namespace App;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         $model->q_code = IdGenerator::generate(['table' => $model->getTable(), 'length' => 11,'field' => 'q_code', 'prefix' => 'P'.date('Ym'),'reset_on_prefix_change' => true]);
    //     });
    // }

    // public function quotation()
    // {
    //     return $this->belongsTo(Quotation::class, 'quotations_id', 'id');
    // }

    public function scopeFindId($query,$id)
    {
        return $query->where('id',$id);
    }
}
