<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'product_id', 'service_id', 'quantity', 'total', 'totalOrder', 'status'];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'orders', 'user_id', 'cart_id');
    }
    
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
}
