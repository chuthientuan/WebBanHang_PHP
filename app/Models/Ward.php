<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'tbl_ward';
    protected $primaryKey = 'xaid';
    protected $fillable = [
        'name_ward',
        'type',
        'maqh'
    ];
    public $timestamps = false;
}
