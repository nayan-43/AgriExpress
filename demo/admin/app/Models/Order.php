<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
 use HasFactory;
 protected $fillable=['user_id','order_number','order_status','payment_status','payment_mode','subtotal','discount','eco_tax','shipping','total_price','coupon_code','customer_note','placed_at'];
 protected function casts(): array{return ['order_status'=>'integer','payment_status'=>'integer','subtotal'=>'decimal:2','discount'=>'decimal:2','eco_tax'=>'decimal:2','shipping'=>'decimal:2','total_price'=>'decimal:2','placed_at'=>'datetime'];}
 public function user(){return $this->belongsTo(User::class);}
 public function items(){return $this->hasMany(OrderItem::class);}
 public function addresses(){return $this->hasMany(OrderAddress::class);}
 public function shippingAddress(){return $this->hasOne(OrderAddress::class)->where('type','shipping');}
 public function billingAddress(){return $this->hasOne(OrderAddress::class)->where('type','billing');}
 public function payments(){return $this->hasMany(Payment::class);}
 public function couponUsages(){return $this->hasMany(CouponUsage::class);}
 public function reviews(){return $this->hasMany(Review::class);}
}
