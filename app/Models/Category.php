<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_category_product';
    protected $primaryKey = 'category_id';
    protected $fillable = [
        'category_name',
        'category_desc',
        'category_status'
    ];
    public $timestamps = false;
    
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
