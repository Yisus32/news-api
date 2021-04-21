<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Client extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'Clients';

    
}