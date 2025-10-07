<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'category_id',
        'brand_id',
        'product_name',
        'product_desc',
        'product_content',
        'product_price',
        'product_image',
        'product_status',
        'product_quantity',
        'product_sold'
    ];
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'product_id');
    }

    public function orders()
    {
        return $this->belongsToMany(
            Order::class,
            'tbl_order_details',
            'product_id',
            'order_id'
        )->withPivot('product_sales_quantity', 'product_price');
    }
}
