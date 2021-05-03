<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Branch extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'branches';

    protected $fillable = ['id','client_id', 'msa_account','code', 'name', 'address', 'coordinate', 'image', 'phones', 'status', 
    'sector_id'];

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

    
}