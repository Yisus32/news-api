<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Coin extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'branches';

    protected $fillable = ['id','name', 'symbol','rate', 'description'];

}