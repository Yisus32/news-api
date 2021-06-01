<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Hole extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'holes';

    protected $fillable = ['code', 'name', 'descriptions'];
}