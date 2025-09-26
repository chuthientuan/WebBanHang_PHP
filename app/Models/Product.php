<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'category_id ',
        'brand_id ',
        'product_name',
        'product_desc',
        'product_content',
        'product_price',
        'product_image',
        'product_status'
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
}
