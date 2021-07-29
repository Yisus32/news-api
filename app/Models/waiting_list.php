<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class waiting_list extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'waiting_list';
    protected $fillable =['user_id','name','date','sta','start_hour','end_hour'];
}