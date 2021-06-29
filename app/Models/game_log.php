<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class game_log extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'game_log';
    protected $fillable=['id','user_id','auser_id','car_id','hol_can','gro_id','fecha'];
}