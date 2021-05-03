<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Sub_Activity extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'sub_Activity';

    protected $fillable = ['id','name', 'detail'];

}