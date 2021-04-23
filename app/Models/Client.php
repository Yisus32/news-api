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
    public function activities(){
        return $this->hasMany(Activity::class,'client_id','id');
    }

}