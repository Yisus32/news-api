<?php

namespace App\Events;

use App\Models\Reservation;
use App\Models\waiting_list;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Dispatcher;
use Illuminate\Queue\SerializesModels;

class waitlist
{
    use InteractsWithSockets, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $lisw;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function __construct($lisw)
    {
        $this->reservation = $lisw;
    }
}