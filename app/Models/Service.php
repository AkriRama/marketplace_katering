<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;

    protected $fillable = ['cover', 'name','price','description','status'];

    public function Sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'service_product', 'service_id', 'product_id');
    }
}
