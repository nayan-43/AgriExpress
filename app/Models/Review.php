<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'user_id', 'order_id', 'rating', 'title', 'comment', 'is_verified_purchase', 'status'];
    protected function casts(): array
    {
        return ['rating' => 'integer', 'is_verified_purchase' => 'boolean', 'status' => 'boolean'];
    }
    public function scopePending($query)
    {
        return $query->where('status', false);
    }
    public function scopeApproved($query)
    {
        return $query->where('status', true);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Approved' : 'Pending';
    }
}
