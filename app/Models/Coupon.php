<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
 use HasFactory;
 protected $fillable=['code','type','value','minimum_order_amount','maximum_discount','usage_limit','usage_limit_per_user','starts_at','expires_at','status'];
 protected function casts(): array{return ['value'=>'decimal:2','minimum_order_amount'=>'decimal:2','maximum_discount'=>'decimal:2','usage_limit'=>'integer','usage_limit_per_user'=>'integer','starts_at'=>'datetime','expires_at'=>'datetime','status'=>'boolean'];}
 public function usages(){return $this->hasMany(CouponUsage::class);}
}
