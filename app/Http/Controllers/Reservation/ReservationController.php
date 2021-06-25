<?php

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Core\ReportService;
use App\Models\Guest;
use App\Models\Reservation;
use App\Services\Reservation\ReservationService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

/** @property ReservationService $service */
class ReservationController extends CrudController
{
    protected $validateRegister = [];

    public function __construct(ReservationService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'teetime_id' => 'required', 
            'hole_id' => 'required',
 //           'date' => 'required',
   //         'start_hour' => 'required',
     //       'end_hour' => 'required',
            'owner' => 'required'
        ];

        $this->validateRegister = [
            'owner' => 'required',
            'status' => 'required'
        ];

        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function take($id,Request $request){

        return $this->service->take($id, $request);
    }

    public function reservation_register($id, Request $request){

        $validator = Validator::make($request->all(), array_merge($this->validateRegister, $this->validateDefault),$this->messages);
        if ($validator->fails()) {
            return response()->json(["error"=>true,"message"=>$this->parseMessageBag($validator->getMessageBag())],422);
        }
        return $this->service->reservation_register($id, $request);
    }

    public function report(Request $request){
        
        $reservations= Reservation::where('status', '=', 'registrado')->where('date', '=', "$request->date")->orderBy('hole_id')
                        ->orderby('id')->get();
        
        $headers = ["Authorization" => $request->input('token')];
 
        $user = $this->getUser($request);
        foreach ($reservations as $reservation) {

            if (isset($reservation->guests)) {
                $reservation->guests = $this->guest_names($reservation->guests);
            }
            
        }
        $index=[
            'hora'=>'start_hour',
            'dueño'=>'owner_name',
            'socios'=>'partners_name',
            'invitados'=>'guests'
        ];
        $info []=$reservations;
        $report = new ReportService();
        $report->indexPerSheet([$index]);
        $report->dataPerSheet($info);
        $report->index($index);
        $report->data($reservations);
        //$report->external();
        $report->username($user->full_name);
        $report->getAccountInfo(1);
        
        return $report->report("automatic","Reservaciones",null,null,false,1);
    }

    private function guest_names($guests){
        $guests = str_replace('{', '', $guests);
        $guests = str_replace('}', '', $guests);
        $guests = explode(',', $guests);
        $guest_names= array();
        $i = 0;
        foreach ($guests as $guest) {
           $model = Guest::where('id', '=', $guest)->first();
           $guest_names[$i] = $model->full_name;
           $i++;
        }
        $guest_names = (new Reservation())->formatTypeArray($guest_names);

        return $guest_names;

    }

    public function getUser($request){
        $client = new Client();

        $user = $client->get(env('USERS_API') . 'get/user/' . $request->user_id);
        if ($user->getStatusCode() == 200) {
            $user = json_decode($user->getBody())->value;
            
            
        }
        return $user->user;
    }
}