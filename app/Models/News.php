<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class News extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'news';
    protected $fillable = ['code','title','photo','video_url','content','type_id','tags','author','account','created_at','updated_at'];

    public function type(){
        return $this->hasOne('App\Models\Type');
    }
}