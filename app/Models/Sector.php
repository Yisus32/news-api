<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Sector extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'sectors';

    protected $fillable = ['id','country', 'state','city', 'sector', 'price', 'geofence'];

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