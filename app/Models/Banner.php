<?php

namespace App\Models;

use App\Support\Supabase;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'eyebrow',
        'title',
        'description',
        'cta_label',
        'cta_url',
        'image',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function getImageUrlAttribute(): ?string
    {
        return Supabase::url($this->image);
    }
}
