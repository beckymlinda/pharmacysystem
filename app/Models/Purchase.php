<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'total_cost',
        'supplier',
        'purchase_date',
        'batch_number',
        'expiry_date',
        'invoice_number',
        'remarks'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
