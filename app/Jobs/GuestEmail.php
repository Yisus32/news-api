<?php

namespace App\Jobs;

use App\Http\Mesh\NotificationService;
use App\Models\Reservation;

class GuestEmail extends Job
{
    protected $emails;
    protected $reservation;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails, $reservation)
    {
        $this->emails = $emails;
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

        $email_receptor = explode(",",$this->emails);
        $guests = explode(",", $reservation->guests_name);
        $i = 0;
        
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
        
        return true;
    }
}
