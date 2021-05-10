<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Bank extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'banks';
    protected $fillable = ['id','coin_id', 'country','name', 'description'];

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