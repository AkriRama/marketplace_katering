<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'order_id', 'product_id', 'service_id', 'name', 'price', 'quantity', 'total', 'totalOrder', 'method', 'status', 'service', 'status_service' ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
}
