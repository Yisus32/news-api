<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Aplication extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'aplications';

    protected $fillable = ['id','client_id', 'app_name','email', 'user', 'wallet_number', 'detail', 'deleted'];

    public function client(){
        return $this->belongsTo(Client::class, 'id', 'client_id');
    }


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeletedScope);
    }
}