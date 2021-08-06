<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class alq_car extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'alq_car';
    protected $fillable=['user_id','car_id','hol_id','gro_id','id_hole','user_name','obs','tipo_p','can_p','user_num'];
}