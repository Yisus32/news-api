<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Sector extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'sectors';

    protected $fillable = ['id','country', 'state','city', 'sector', 'price', 'geofence'];

}