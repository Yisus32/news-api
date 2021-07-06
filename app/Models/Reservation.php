<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Reservation extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'reservations';

    protected $fillable = ['teetime_id', 'hole_id','date', 'start_hour', 'end_hour', 'owner', 'partners', 'guests', 
    'status','partners_name','owner_name','guests_email','created_at', 'updated_at'];

    protected $hidden = [];

    public function teetime(){
        return $this->belongsTo(Teetime::class, 'id', 'teetime_id');
    }

    /**
     * @named Funcion para convertir a string (Estandarizada)
     * @param $value
     * @return mixed
     */
    public function formatTypeArray($value){
        if (is_int($value) AND intval($value)>0){
            return '{'.$value.'}';
        }
        if (is_array($value)){
            $value = array_unique($value);
            if(count($value)>0) {
                $value = json_encode($value);
                $value = str_replace('"', '', $value);
                $value = str_replace('[', '{', $value);
                $value = str_replace(']', '}', $value);
                return $value;
            }
        }

        return '{}';
    }
}