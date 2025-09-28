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
        'unit_id',
        
        'purchase_frequency',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    protected $dates = ['expiry_date'];

    // Accessor for formatted expiry date
    

    // Accessor for days until expiry
    public function getDaysUntilExpiryAttribute()
    {
        return $this->expiry_date ? now()->diffInDays($this->expiry_date, false) : null;
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    public function saleItems()
{
    return $this->hasMany(SaleItem::class);
}

}
