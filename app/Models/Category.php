<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['parent_id', 'name', 'slug', 'menu_name', 'description', 'image', 'banner', 'meta_title', 'meta_description', 'meta_keywords', 'sort_order', 'status'];
    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'status' => 'boolean'];
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getImageUrlAttribute(): ?string
    {
        return \App\Support\Supabase::url($this->image);
    }
    public function getBannerUrlAttribute(): ?string
    {
        return \App\Support\Supabase::url($this->banner);
    }
}
