<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class bitatoalla extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'bitatoalla';
    protected $fillable =['fec_asig','id_toalla','sta','fec_ult','user_id','user_name'];
}