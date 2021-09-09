<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable=[
        'employee_id','client_id','empty','filled',
    ];
    protected $primaryKey='order_id';
    protected $table='orders';
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
