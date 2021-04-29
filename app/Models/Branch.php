<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Branch extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'branches';

    protected $fillable = ['id','msa_account','code', 'name', 'address', 'coordinate', 'image', 'phones', 'status'];

    /**
     * @return HasMany
     */
    public function schedules(){
        return $this->hasMany(Schedule::class,'branch_id','id')
            ->orderBy('day','asc')->orderBy('turn','asc');
    }

    /**
     * @return HasMany
     */
    public function clients(){
        return $this->hasMany(Client::class,'branch_id','id');
    }
}