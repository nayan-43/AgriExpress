<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'logo', 'description', 'meta_title', 'meta_description', 'status'];
    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getLogoUrlAttribute(): ?string
    {
        return \App\Support\Supabase::url($this->logo);
    }
}
