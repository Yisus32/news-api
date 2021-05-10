<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Branch extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'branches';

    protected $fillable = ['id','client_id', 'msa_account','code', 'name', 'address', 'coordinate', 'image', 'phones', 'status', 
    'sector_id','deleted'];

    /**
     * @return HasMany
     */
    public function schedules(){
        return $this->hasMany(Schedule::class,'branch_id','id')
            ->orderBy('day','asc')->orderBy('turn','asc');
    }

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