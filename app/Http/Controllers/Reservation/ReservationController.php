<?php

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Core\ReportService;
use App\Models\Guest;
use App\Models\Hole;
use App\Models\Reservation;
use App\Services\Reservation\ReservationService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use App\Models\TempData;

//commit de reposicion

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
            'date' => 'required',
            'start_hour' => 'required',
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

    public function _store(Request $data){

        if ($data->header('time') == 'expired') {
            $this->service->restartTeetime($data,$data['teetime_id'],$data['hole_id']);
            return response()->json(['status'=>408,'message'=>'El tiempo de reserva ha expirado'],408);
        }else{
            $data['status'] = 'registrado';
            $this->service->restartTeetime($data,$data['teetime_id'],$data['hole_id']);
            return $this->service->_store($data);
        }   
    }

    public function _update($id, Request $data){
       
        if ($data->header('time') == 'expired') {
            $this->service->restartTeetime($data,$data['teetime_id'],$data['hole_id']);
            return response()->json(['status'=>408,'message'=>'El tiempo de reserva ha expirado'],408);
        }else{
            $data['status'] = 'registrado';
            $this->service->restartTeetime($data,$data['teetime_id'],$data['hole_id']);
            return $this->service->_update($id,$data);
        }
    }

    public function cancelReservation($id){
        return $this->service->cancelReservation($id);
   }

   public function resendInvitation($reservation_id,Request $request){
        return $this->service->resendInvitation($reservation_id,$request);
   }

   public function standByTeetime(Request $request, $id,$hole_id){
     return $this->service->standByTeetime($request, $id,$hole_id);
   }

   public function restartTeetime(Request $request,$id,$hole_id){
        return $this->service->restartTeetime($request,$id,$hole_id);
    }

    public function report(Request $request){
        $report = new ReportService();
 
        $holes = Reservation::all();
       
         
        foreach ($holes as $hole) {
           $data = Reservation::select('reservations.*',
                                        'holes.name as hole_name')
                                         ->where('status','registrado')
                                         ->leftjoin('holes','holes.id','=','reservations.hole_id')
                                         ->where('holes.id',$hole->hole_id)
                                         ->get();
        }     
             
        $report->data($data);
        return $report->report("automatic","Reservaciones",null,null,false,1);
    }

    public function advanceFilter(Request $request){
        return $this->service->advanceFilter($request);
    }

}