<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use App\Models\Invitation;

class Guest extends CrudModel
{
    protected $guarded = ['id'];
    protected $table = 'guests';
    protected $fillable = [
        "full_name", 
        "email", 
        "identifier",
        "card_number", 
        "status",
        "host_id", 
        "host_name", 
        "games_number", 
        "games_number_month",
        "host_number",
        "created_at", 
        "updated_at"
    ];
    protected $hidden = [];

    /**
     * @return HasMany  
     */ 
    public function documents(){
        return $this->hasMany(Document::class,'guest_id','id');
    }

    public function createInvitation($data,$guest){
       
            $invitation = new Invitation;
            $invitation->reservation_id = $data['reservation_id'];
            $invitation->guest_email = $guest['email'];
            $invitation->status = 'aceptado';
            $invitation->save();
            return $invitation; 

        return [];
    }
}