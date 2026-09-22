<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
 use HasFactory;
 protected $fillable=['product_id','sku','price','sale_price','stock','image','status'];
 protected function casts(): array{return ['price'=>'decimal:2','sale_price'=>'decimal:2','stock'=>'integer','status'=>'boolean'];}
 public function product(){return $this->belongsTo(Product::class);}
 public function attributeValues(){return $this->belongsToMany(AttributeValue::class,'variant_attribute_values','variant_id','attribute_value_id')->withTimestamps();}
 public function variantAttributeValues(){return $this->hasMany(VariantAttributeValue::class,'variant_id');}
 public function cartItems(){return $this->hasMany(CartItem::class,'variant_id');}
 public function orderItems(){return $this->hasMany(OrderItem::class,'variant_id');}
}
