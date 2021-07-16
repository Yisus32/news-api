<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class toalla extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='toalla';
    protected $fillable=['num','description','status','fec','user_id'];
}