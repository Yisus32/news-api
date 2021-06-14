<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Teetime_type extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'teetime_types';

    protected $fillable = ['code', 'name', 'description'];
}