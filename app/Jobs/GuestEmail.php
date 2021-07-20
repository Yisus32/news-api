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
        $receipt_url = 'https://qarubick2.zippyttech.com/';
        $subject = "Invitación Teetime";
        $message = "Club de Golf Panama le informa que usted ha sido invitado a participar en el teetime 
        Nº$reservation->id que inicia a las $reservation->start_hour del dia $reservation->date auspiciado por $reservation->owner_name. <br>
        para poder asistir al evento necesita registrarse en el sistema. <br> <br> <a href='".$receipt_url."' target='_blank'>Haga click para registrar sus datos</a></p>";
        $mailer = new NotificationService;
        return $mailer->sendEmail($email_receptor,$subject,$message,6,"notificaciones@zippyttech.com");
        return true;
    }
}
