<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Bank extends CrudModel
{
    protected $guarded = ['id'];
}