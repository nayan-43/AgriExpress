<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'brand_id', 'name', 'slug', 'sku', 'short_description', 'description', 'price', 'sale_price', 'stock', 'main_image', 'has_variants', 'featured', 'status', 'meta_title', 'meta_description', 'meta_keywords'];
    protected function casts(): array
    {
        return ['price' => 'decimal:2', 'sale_price' => 'decimal:2', 'stock' => 'integer', 'has_variants' => 'boolean', 'featured' => 'boolean', 'status' => 'boolean'];
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function scopeLowStock($query, int $threshold = 10)
    {
        return $query->where('stock', '<=', $threshold)->where('status', true);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function getMainImageUrlAttribute(): ?string
    {
        return \App\Support\Supabase::url($this->main_image);
    }
}
