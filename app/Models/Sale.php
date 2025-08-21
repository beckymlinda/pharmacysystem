<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'sale_date',
        'payment_method', // optional, store payment type like 'cash', 'mpamba', etc.
        'user_id',        // who made the sale
    ];

    protected $dates = [
        'sale_date',
    ];

    /**
     * The sale items associated with this sale.
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * The user who recorded this sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
    'sale_date' => 'datetime',
];

}
