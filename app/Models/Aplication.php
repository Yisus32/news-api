<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Aplication extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'aplications';

    protected $fillable = ['id','client_id', 'app_name','email', 'user', 'wallet_number', 'detail'];

    public function client(){
        return $this->belongsTo(Client::class, 'id', 'client_id');
    }

}