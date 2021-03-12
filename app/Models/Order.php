<?php

namespace App\Models;

use App\Core\CrudModel;
use Illuminate\Database\Eloquent\Model;
use App\Core\TatucoModel;

class Order extends CrudModel
{
    protected $guarded = ['id'];

    protected $model = 'orders';

    protected $fillable = ['id', 'msa_account', 'quantity', 'total_amount'];

    protected $casts    = [
        "total_amount" => "decimal:2"
      ];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(){
        return $this->hasMany(Product::class,'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statuses(){
        return $this->hasOne(Status::class,'order_id');
    }
}