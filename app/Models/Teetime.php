<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Teetime extends CrudModel
{
    protected $guarded = ['id'];
}