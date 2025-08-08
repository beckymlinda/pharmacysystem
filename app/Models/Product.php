<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Optional: explicitly define table name if different from model name
    // protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     */
   protected $fillable = [
    'name',
    'category',
    'quantity',
    'price',
    'expiry_date',
    'order_price',
    'selling_price',
    'brand',
    'seller',
    'alert_quantity',
    'purchase_frequency',
];
}
