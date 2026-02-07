<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
use App\Models\Size;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'base_price', 'stock', 'size', 'image', 'images'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'base_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_variants')
            ->withPivot('price', 'stock', 'id')
            ->withTimestamps();
    }
}
