<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'tbl_shipping';
    protected $primaryKey = 'shipping_id';
    protected $fillable = [
        'customer_id',
        'shipping_name',
        'shipping_address',
        'shipping_phone',
        'shipping_email',
        'shipping_note'
    ];
    public $timestamps = false;
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function order()
    {
        return $this->hasMany(Order::class, 'shipping_id    ');
    }
}
