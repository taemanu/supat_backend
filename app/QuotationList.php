<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuotationList extends Model
{

    protected $fillable = ['quotations_id', 'q_name', 'amount', 'price'];
    
    /**
     * Get the user that owns the QuotationList
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotations_id', 'id');
    }
}
