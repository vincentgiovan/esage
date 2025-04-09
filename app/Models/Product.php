<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function purchases(){
        return $this->belongsToMany(Purchase::class, "purchase_products");
    }

    public function latest_purchase_product(): HasOne
    {
        return $this->hasOne(PurchaseProduct::class)
            ->join('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->orderByDesc('purchases.purchase_date')
            ->select('purchase_products.*'); // important to avoid getting only joined fields
    }

    public function delivery_orders(){
        return $this->belongsToMany(DeliveryOrder::class, "delivery_order_products");
    }

    public function delivery_order_products(){
        return $this->hasMany(DeliveryOrderProduct::class);
    }

    public function purchase_products(){
        return $this->hasMany(PurchaseProduct::class);
    }

    public function request_items(){
        return $this->belongsToMany(RequestItem::class, "request_item_products");
    }

    public function request_item_products(){
        return $this->hasMany(RequestItemProduct::class);
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters["search"]?? false, function($query, $search) {
            return $query->where("product_name", "like", "%". $search. "%");
        });

        $query->when($filters["condition"]?? false, function($query, $condition) {
            return $query->where("condition", "like", "%". $condition. "%");
        });
    }
}
