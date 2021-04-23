<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Activity extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'activities';

    protected $fillable = ['client_id','name','icon','description'];

    public function client(){
        $this->belongsTo(Client::class,'id','client_id');
    }

}