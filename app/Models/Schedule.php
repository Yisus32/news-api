<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Schedule extends CrudModel
{
    protected $guarded = ['id'];

    protected $table ="schedules";
    protected $fillable = [
        'id', 'day','start_hour','end_hour','turn','branch_id','description','day_description','active'
    ];
    protected $hidden = ["created_at","updated_at"];

    public $days = [
        "null","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado","Domingo"
    ];

    public function Branch(){
        $this->belongsTo(Branch::class,'id','branch_id');
    }
}