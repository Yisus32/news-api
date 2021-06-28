<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class cars_golf extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='cars_golf';
    protected $fillable=['id','price'];
}