<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Break_time extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'break_times';

    protected $fillable = ['teetime_id','start_hour', 'end_hour'];

    /* 
    **
    */
    public function teetime(){
        return $this->belongsTo(Teetime::class, 'id', 'teetime_id');
    }
}