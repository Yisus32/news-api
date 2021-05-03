<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Client_rate extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'client_rate';

    protected $fillable = ['id','coin_id', 'rate','description'];

}