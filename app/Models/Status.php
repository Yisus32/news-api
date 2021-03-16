<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Status extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'statuses';

    protected $fillable = ['id', 'name', 'detail'];

    protected $hidden = ['created_at', 'updated_at'];

    public function order(){
        $this->belongsTo(Order::class,'order_id');
    }
}