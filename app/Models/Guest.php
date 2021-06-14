<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Guest extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'guests';

    protected $fillable = ["full_name", "email", "identifier", "status"];

    /**
     * @return HasMany
     */
    public function documents(){
        return $this->hasMany(Document::class,'guest_id','id');
    }

}