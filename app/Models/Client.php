<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Client extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'clients';

    protected $fillable = ['id','commerce_name','rif','msa_account', 'logo'];

    /**
     * @return HasMany
     */
    public function branches(){
        return $this->hasMany(Branch::class,'client_id','id');
    }

    public function bank_account(){
        return $this->hasMany(Bank_account::class,'client_id','id');
    }

    public function aplications(){
        return $this->hasMany(Aplication::class,'client_id','id');
    }

}