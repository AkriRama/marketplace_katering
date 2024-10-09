<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Product extends Model
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;

    protected $fillable = [
        'code_product','name','price', 'discount','stock','description','cover','slug'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    // @return \Illuminate\Database\Eloquent\Relations\BelongsToMany;

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category','product_id','category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'carts', 'product_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

}
