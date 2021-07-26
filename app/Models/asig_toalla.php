<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class asig_toalla extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='asig_toalla';
    protected $fillable=['id_toalla','user_id','fec_ini','fec_fin','user_name'];
}