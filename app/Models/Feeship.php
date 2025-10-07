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

    public function city()
    {
        return $this->belongsTo(City::class, 'fee_matp', 'matp');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'fee_maqh', 'maqh');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'fee_xaid', 'xaid');
    }
}
