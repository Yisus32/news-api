<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Bank_account extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'bank_account';

    protected $fillable = ['client_id','bank_id', 'name', 'identifier','phone', 'account_number', 'account_type', 'detail'];

    
    public function Client(){
        $this->belongsTo(Client::class,'id','client_id');
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