<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    protected $fillable=[
        'client_id','used_bottles','amount',
    ];
    protected $primaryKey='bill_id';
}
