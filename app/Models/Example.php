<?php

namespace App\Models;

use App\Scopes\DeletedScope;
use App\Core\TatucoModel;

class Example extends TatucoModel
{
    protected $guarded = ['id'];

    protected $fillable = ['id','name','description','code','deleted'];

    protected $hidden = ['deleted'];



    /** 
     * The "booting" method of the model.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeletedScope());
    }
}