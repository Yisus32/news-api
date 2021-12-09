<?php

namespace App\Jobs;

use App\Models\waiting_list;

class wait_list_job extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $date;
    protected $hour;
    public function __construct($dat,$hou)
    {
        $this->date=$dat;
        $this->hour=$hou;


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $espera=waiting_list::where('date',$this->date)->where('start_hour',$this->hour)->get();
        foreach ($espera as $key) {
           
        }
    }
}
