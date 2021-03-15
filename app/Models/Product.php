<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Product extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'products';

    protected $fillable = ['id', 'order_id','name','detail', 'quantity', 'price'];

    protected $casts    = [
        "price" => "decimal:2"
      ];

    protected $hidden = ['created_at', 'updated_at'];

    public function order(){
        $this->belongsTo(Order::class,'order_id');
    }
}
