<?php

namespace App\Models;

use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Model;

class Product extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'orders';

    protected $fillable = ['id', 'name','detail', 'quantity', 'price'];

    protected $casts    = [
        "price" => "decimal:2"
      ];

    protected $hidden = ['created_at', 'updated_at'];

    public function order(){
        $this->belongsTo(Order::class,'order_id');
    }
}
