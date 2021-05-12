<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Scopes\DeletedScope;

class Client extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'clients';

    protected $fillable = ['id','commerce_name','rif','msa_account', 'logo', 'activity', 'sub_activity'];

    /**
     * @return HasMany
     */
    public function branches(){
        return $this->hasMany(Branch::class,'client_id','id');
    }

    public function bank_account(){
        return $this->hasMany(Bank_account::class,'client_id','id');
    }

    public function aplications(){
        return $this->hasMany(Aplication::class,'client_id','id');
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
    public function getActivityIdAttribute($val)
    {
        $new_val = $value = str_replace('{', '[', $val);
        $new_val = $value = str_replace('}', ']', $new_val);
        return json_decode($new_val);
    }

}