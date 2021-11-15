<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;
use Carbon\Carbon;

class Reservation extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = 'reservations';

    protected $fillable = ['teetime_id', 'hole_id','date', 'start_hour', 'owner', 'partners', 'guests', 
    'status','partners_name','owner_name','guests_email','guests_name','confirmations_number','created_at', 'updated_at'];

    protected $hidden = [];

    public function teetime(){
        return $this->belongsTo(Teetime::class, 'id', 'teetime_id');
    }
    /**
     * @return HasMany
     */
    public function invitations(){
        return $this->hasMany(Invitation::class,'reservation_id','id');
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

    public function setCancelDate($date,$time,$cancel_time){
    
        $teetime_cancel_time = Carbon::createFromFormat('Y-m-d H:i:s', $date.' '.$time);
        $teetime_cancel_time = Carbon::parse($teetime_cancel_time->modify('-'.$cancel_time.' hours'))->format('Y-m-d H:i:s',env('APP_TIMEZONE'));
        
        return $teetime_cancel_time;
    }

    public function checkPartners($owner,$partners){
        $partners = json_decode($partners);
        $check = array_search($owner, $partners);

        return $check;
    }

    public function isAdmin($user,$owner,$partners,$guests){
        foreach (json_decode($partners) as $partner) {
            $players[] = $partner;
        }

        foreach (json_decode($guests) as $guest) {
            $players[] = $guest;
        }

        $players[] = $owner;

        $check = array_search($user, $players);

        return $check;
    }

    public function createInvitation($stored){

        if ($stored['guests'] != null) {
            $guests = json_decode($stored['guests']);
            foreach ($guests as $guest) {
                $invitation = new Invitation;
                $invitation->reservation_id = $stored->id;
                $invitation->guest = $guest;
                $invitation->save();
            }   
        }

        if ($stored['partners'] != null) {
            $partners = json_decode($stored['partners']);
            foreach ($partners as $partner) {
                $invitation = new Invitation;
                $invitation->reservation_id = $stored->id;
                $invitation->partner = $partner;
                $invitation->save();
            } 
        }

        if ($stored['guests_email'] != null) {
            $guests_email = explode(',', $stored['guests_email']);
            foreach ($guests_email as $guest) {
                $invitation = new Invitation;
                $invitation->reservation_id = $stored->id;
                $invitation->guest_email = $guest;
                $invitation->save();
            } 
        }
        
        return $invitation; 
    }
}