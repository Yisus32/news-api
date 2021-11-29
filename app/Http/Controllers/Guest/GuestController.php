<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Http\Mesh\NotificationService;
use App\Models\Guest;
use App\Services\Guest\GuestService;
/** @property GuestService $service */
class GuestController extends CrudController
{
    public function __construct(GuestService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {       
            if (!isset($request->status)) {
                $request->status = 'No confirmado';
                $request["status"] = 'No confirmado';
            }

            return parent::_store($request);
    }

    public function email(Request $request){
        
        $subject = "Invitación Teetime";
        
        $guest_exist = Guest::where('email','=', "$request->email")->first();
        if($guest_exist) {
            return Response()->json(["error" => true,"message" => "El email ingresado ya se encuentra registrado"], 400);
        }
       // $object_guest = Guest::find($guest);
        $name =  $request->full_name;
        $email = $request->email;
        $owner_name = $request->owner_name;
        $owner_number = $request->host_number;
        $owner_id = $request->host_id;
      
        $receipt_url = 'https://'.env('FRONT_URL')."/guest/register-guest/$name/$email/$owner_id/$owner_name/$owner_number";

        if ($email) {
            $message = "Estimado $name el socio $owner_name lo ha invitado a registrarse al <b>Club de Golf Panamá</b>
            . Debe registrar sus datos en el siguiente enlace
            <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click para registrarse</a>";
      
            $mailer = new NotificationService;
            $mailer->sendEmail($email,$subject,$message,6,"notificaciones@zippyttech.com");
        
        }
        return Response()->json(["message" => "Invitacion enviada correctamente"], 200);

    }

    public function acceptInvitation($id){

        $guest = Guest::where('id',$id)->first();
        $guest->status = "confirmado";
        $guest->save();

        header('Location: '.'https://'.env('FRONT_URL').'/teetime/reservations');
        exit();
    }

}