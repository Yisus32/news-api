<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class TempData extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'temp_data';
    protected $fillable = ['teetime_id','created_at','updated_at'];
}