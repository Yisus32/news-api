<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Branch extends CrudModel
{
    protected $guarded = ['id'];



    
    /**
     * @return HasMany
     */
    public function schedules(){
        return $this->hasMany(WorkSchedule::class,'client_id','id')
            ->orderBy('day','asc')->orderBy('turn','asc');
    }
}