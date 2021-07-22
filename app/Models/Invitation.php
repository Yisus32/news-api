<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Core\CrudModel;

class Invitation extends CrudModel
{
    protected $guarded = ['id'];

    protected $table = "invitations";

    protected $fillable = ["reservation_id", "guest", "partner", "guest_email", "status"];

    public function Reservation(){
        return $this->belongsTo(Reservation::class, 'id', 'reservation_id');
    }
}