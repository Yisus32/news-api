<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class number_holes extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='number_holes';
    protected $fillable=['id', 'hol_can'];
}