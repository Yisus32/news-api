<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Bank_account extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'bank_account';

    protected $fillable = ['client_id', 'name', 'phone', 'account_number', 'type', 'detail'];

    
    public function Branch(){
        $this->belongsTo(Branch::class,'id','branch_id');
    }
}