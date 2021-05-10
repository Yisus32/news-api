<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Coin extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'coins';

    protected $fillable = ['id','name', 'symbol','rate', 'description'];


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