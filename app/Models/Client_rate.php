<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Client_rate extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'client_rate';

    protected $fillable = ['id','client_id','coin_id', 'rate','description', 'deleted'];

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