<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class OrderBuy extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'purchase_id' ,'product_id', 'quantity', 'total', 'totalOrder'];

    
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    
}
