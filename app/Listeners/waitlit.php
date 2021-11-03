<?php

namespace App\Listeners;

use App\Events\waitlist;
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
          var_dump('holissss');
    }
}
