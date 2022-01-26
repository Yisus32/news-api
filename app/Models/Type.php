<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Type extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'type';
    protected $fillable = ['icon','type','description','created_at','updated_at'];

    public function new(){
        return $this->belongsTo('App\Models\News');
    }
}