<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class group extends CrudModel
{
    protected $guarded = ['id'];
    protected $table='group';
    protected $fillable=['id','cod','description'];
}