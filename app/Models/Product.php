<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'quantity',
        'expiry_date',
        'order_price',
        'selling_price',
        'brand',
        'seller',
        'alert_quantity',
        'purchase_frequency',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];
}
