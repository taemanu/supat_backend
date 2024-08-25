<?php

namespace App;

use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    // use IdGenerator;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->qt_code = IdGenerator::generate(['table' => $model->getTable(), 'length' => 11,'field' => 'qt_code', 'prefix' => 'QT'.date('Ym'),'reset_on_prefix_change' => true]);
        });
    }

    /**
     * Get all of the quotationList for the Quotation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotationList()
    {
        return $this->hasMany(QuotationList::class, 'quotations_id', 'id');
    }
}
