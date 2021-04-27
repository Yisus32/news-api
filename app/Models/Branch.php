<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Branch extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'branches';

    protected $fillable = ['id','msa_account','owner','code', 'name', 'address', 'coordinate', 'image', 'phones', 'status'];

    /**
     * @return HasMany
     */
    public function schedules(){
        return $this->hasMany(WorkSchedule::class,'client_id','id')
            ->orderBy('day','asc')->orderBy('turn','asc');
    }

    /**
     * @return HasMany
     */
    public function clients(){
        return $this->hasMany(Client::class,'client_id','id');
    }
}