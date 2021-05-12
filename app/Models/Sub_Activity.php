<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Sub_Activity extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'sub_activity';

    protected $fillable = ['id','name', 'detail'];

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