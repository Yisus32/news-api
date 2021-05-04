<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Activity extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'activities';

    protected $fillable = ['name','icon','description'];

    

}