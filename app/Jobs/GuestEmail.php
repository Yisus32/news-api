<?php

namespace App\Jobs;

use App\Http\Mesh\NotificationService;
use App\Models\Guest;
use App\Models\Invitation;
use App\Models\Reservation;

class GuestEmail extends Job
{
    protected $request;
    protected $reservation;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reservation)
    {
       // $this->request = $request;
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $reservation = Reservation::find($this->reservation);
      
        $guests = explode(",", $reservation->guests_name);
        $i = 0;

        if (!empty($reservation->guests_email)) {
            $email_receptor = explode(",",$reservation->guests_email);
          
            // correos de invitados nuevos
            foreach ($email_receptor as $email) {
                
                $receipt_url = 'https://qarubick2.zippyttech.com/';
                $subject = "Invitación Teetime";
                if (isset($guests[$i]) and $guests[$i] != null) {
                    $name = $guests[$i];
                }else{
                    $name = "invitado";
                }
                
            
                $message = "Estimado $name el socio $reservation->owner_name lo ha invitado al <b>Club de Golf Panamá</b>
                para que forme parte del teetime. Para poder aceptar la solicitud necesita llenar sus datos en el siguiente enlace
                <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click para registrar sus datos</a>";
                $mailer = new NotificationService;
                $mailer->sendEmail($email,$subject,$message,6,"notificaciones@zippyttech.com");
                $i++;
            }
        }
        
        //enviar email a invitados registrados para que acepten el teetime
        if (!empty($reservation->guests)) {
            
            $array_guest = $reservation->guests;
            // checa que si se hayan registrado invitados en la reservacion
            if (strlen($array_guest) > 2) {
                $array_guest = str_replace("{", '', $array_guest);
                $array_guest = str_replace("}", '', $array_guest);
                $array_guest = explode(',', $array_guest);
                
                foreach ($array_guest as $guest){
                    $invitation = new Invitation();
                    $invitation->reservation_id = $this->reservation;
                    $invitation->guest = $guest;
                    $invitation->save();
                    
                    $receipt_url = 'https://qarubick2teetime.zippyttech.com/accept/invitation/' . $invitation->id;
                    $subject = "Invitación Teetime";
                    
                    $object_guest = Guest::find($guest);
                    $name =  $object_guest->full_name;
                    $email = $object_guest->email;
                    $email = filter_var($email,FILTER_VALIDATE_EMAIL);
    
                    $message = "Estimado $name el socio $reservation->owner_name lo ha invitado al <b>Club de Golf Panamá</b>
                    para que forme parte del teetime. Para aceptar la solicitud solo debe hacer click al siguiente enlace
                    <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
                    if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
                        $mailer = new NotificationService;
                        $mailer->sendEmail($email,$subject,$message,6,"notificaciones@zippyttech.com");
                    }
                    
                    $i++;
                }
            }
            
        }

         //enviar email a socios para que acepten el teetime (queda por terminar)
     /*    if (!empty($reservation->partners)) {
            $array_partners = $reservation->partners;
            $array_partners = str_replace("{", '', $array_partners);
            $array_partners = str_replace("}", '', $array_partners);
            $array_partners = explode(',', $array_partners);

            foreach ($array_partners as $partner){
                $invitation = new Invitation();
                $invitation->reservation_id = $this->reservation;
                $invitation->partner = $partner;
                $invitation->save();
                
                $receipt_url = 'https://qarubick2teetime.zippyttech.com/accept/invitation/' . $invitation->id;
                $subject = "Invitación Teetime";
                
                $object_guest = Guest::find($guest);
                $name =  $object_guest->full_name;
                $email = $object_guest->email;
                $email = filter_var($email,FILTER_VALIDATE_EMAIL);

                $message = "Estimado $name el socio $reservation->owner_name lo ha invitado al <b>Club de Golf Panamá</b>
                para que forme parte del teetime. Para aceptar la solicitud solo debe hacer click al siguiente enlace
                <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click para aceptar la invitación</a>";
                if (filter_var($email,FILTER_VALIDATE_EMAIL)) {
                    $mailer = new NotificationService;
                    $mailer->sendEmail($email,$subject,$message,6,"notificaciones@zippyttech.com");
                }
                
                $i++;
            }
        }*/
        
        
        return true;
    }
}
