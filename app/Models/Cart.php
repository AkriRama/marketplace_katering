<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'product_id', 'service_id', 'name', 'price', 'quantity', 'total', 'product_service'];

    public function product()
    {
        return $this->belongsTo('carts', 'product_id', 'user_id');
    }
}
