<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'tbl_order';
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'customer_id',
        'shipping_id',
        'payment_id',
        'order_status',
        'created_at'
    ];
    public $timestamps = false;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }
    public function products()
    {
        return $this->belongsToMany(
            Product::class,          // Model muốn liên kết tới
            'tbl_order_details',     // Tên bảng trung gian
            'order_id',              // Khóa ngoại của model hiện tại trong bảng trung gian
            'product_id'             // Khóa ngoại của model liên kết trong bảng trung gian
        )->withPivot('product_sales_quantity', 'product_price'); // Lấy thêm các cột trong bảng trung gian
    }
}
