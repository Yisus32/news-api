<?php

namespace App\Http\Controllers\Invitation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\Invitation;
use App\Models\Reservation;
use App\Services\Invitation\InvitationService;
use Illuminate\Http\Response;

/** @property InvitationService $service */
class InvitationController extends CrudController
{
    public function __construct(InvitationService $service)
    {
        parent::__construct($service);
    }

    public function accept_invitation($id){
        $invitation = Invitation::find($id);

        if (!$invitation) {
            return Response()->json(["error" => true ,"message" => "Invitación no encontrada"], 404);
        }

        $invitation->status = "aceptado";

        $reservation = Reservation::find($invitation->reservation_id);

        $reservation->confirmations_number = $reservation->confirmations_number + 1;

        $reservation->save();

        return Response()->json(["message" => "Invitación aceptada correctamente"], 200);
    }
}