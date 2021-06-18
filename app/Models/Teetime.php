<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Teetime extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'teetimes';

    protected $fillable = ['type_id', 'start_date', 'end_date', 'min_capacity', 'max_capacity', 'time_interval', 'available',
    'cancel_time', 'start_hour', 'end_hour', 'target', 'days', 'user_id', 'user_name'];


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

    /**
     * @return HasMany
     */
    public function break_times(){
        return $this->hasMany(Break_time::class,'teetime_id','id');
    }

    /**
     * @return HasMany
     */
    public function reservations(){
        return $this->hasMany(Reservation::class,'teetime_id','id');
    }
}