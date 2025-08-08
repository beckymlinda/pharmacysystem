<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'total_cost',
        'supplier',
        'purchase_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
