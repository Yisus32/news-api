<?php

namespace App\Listeners;

use App\Events\waitlist;
use App\Models\waiting_list;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class waitlit
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  waitlist  $event
     * @return void
     */
    public function handle(waitlist $event)
    {
         $r= $event->reservation;
         $c=waiting_list::where('date',$r->fecha)->where('start_hour',$r->hora)->get();
         dd($c);
    }
}
