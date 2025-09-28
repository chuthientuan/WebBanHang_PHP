<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feeship extends Model
{
    protected $table = 'tbl_feeship';
    protected $primaryKey = 'fee_id'; 
    protected $fillable = [
        'fee_matp',
        'fee_maqh',
        'fee_xaid',
        'fee_feeship'
    ];
    public $timestamps = false;
}
