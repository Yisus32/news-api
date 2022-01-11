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
use App\Http\Mesh\UserService;
use Carbon\CarbonPeriod;

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
        $user = new UserService();
        $holes = Reservation::all();
       
         
        foreach ($holes as $hole) {
           $reservations = Reservation::select('reservations.*',
                                        'holes.name as hole_name')
                                         ->where('status','registrado')
                                         ->when($request->t_date, function ($query,$t_date){
                                            $t_date = explode('_',$t_date);
                                            return $query->where('reservations.date','>=',$t_date[0])
                                                         ->where('reservations.date','<=',$t_date[1]);
                                         })
                                         ->when($request->r_date, function ($query,$r_date){
                                            $r_date = explode('_',$r_date);
                                            return $query->where('reservations.created_at','>=',$r_date[0].' 00:00:00')
                                                         ->where('reservations.created_at','<=',$r_date[1].' 23:59:59');
                                         })
                                         ->when($request->owner, function ($query,$owner){
                                            return $query->where('reservations.owner',$owner);
                                         })
                                         ->when($request->partner, function ($query, $partner) {
                                            return $query->where('reservations.partners_name','ILIKE','%'.$partner.'%');
                                        })
                                        ->when($request->guest, function ($query,$guest) {
                                            return $query->where('reservations.guests_name','ILIKE','%'.$guest.'%')
                                                         ->orWhere('reservations.guests_email','ILIKE','%'.$guest.'%');
                                        })
                                         ->leftjoin('holes','holes.id','=','reservations.hole_id')
                                         ->orderBy('reservations.date','asc')
                                         ->orderBy('reservations.start_hour','asc')
                                         ->get();
        }
        
       if (!$reservations->isEmpty()) {
           foreach ($reservations as $reservation) {

            $players = array_merge(json_decode($reservation->partners),json_decode($reservation->guests));

            
                                 $data[] = ["reservation_id" => Carbon::parse($reservation->date)->format('d-m-Y'),
                                            "hole_id" => $reservation->hole_id,
                                            "hole_name" => $reservation->hole_name,
                                            "start_hour" => $reservation->start_hour,
                                            "date" => $reservation->date,
                                            "players" => $this->searchPlayers($players,$user),
                                            "owner" =>  $reservation->owner_name
                                          ]; 
        }

        $data = $this->groupArray($data,"date");

        for ($i=0; $i < count($data) ; $i++) {            
            $data[$i]["groupeddata"] = $this->groupArray($data[$i]["groupeddata"],"hole_id");
            
        }

         /**for ($i=0; $i < count($data) ; $i++) { 
           $data[$i]["date"] = Carbon::parse($data[$i]["groupeddata"][0]["date"])->format('D d/m/Y');    
        }**/
        
        $report->data($data);
        return $report->report("automatic","Reservaciones",null,null,false,1);
       }else{
            return response()->json(["status"=>400,"message"=> "No hay registros para este reporte"],400);
       }
        
    }
    
    function groupArray($array,$groupkey){
         if (count($array)>0)
        {
            $keys = array_keys($array[0]);
            $removekey = array_search($groupkey, $keys);        
        if ($removekey===false)
            return array("Clave \"$groupkey\" no existe");
        else
            unset($keys[$removekey]);
        $groupcriteria = array();
        $return=array();
        foreach($array as $value)
        {
            $item=null;
            foreach ($keys as $key)
            {
                $item[$key] = $value[$key];
            }
            $busca = array_search($value[$groupkey], $groupcriteria);
            if ($busca === false)
            {
                $groupcriteria[]=$value[$groupkey];
                $return[]=array($groupkey=>$value[$groupkey],'groupeddata'=>array());
                $busca=count($return)-1;
            }
            $return[$busca]['groupeddata'][]=$item;

        }

        return $return;
     }
     else
        return array();
    }

    public function searchPlayers(array $players,$user){
        dd($players);
       foreach ($players as $player) {

           $guest = Guest::where('id',$player)->first();

           if ($guest != null) {
               $array[] = ["ref" => $guest->card_number, "full_name" => $guest->full_name];
           }else{
                $array[] = ["ref" => $user->getUsersById($player)->user->n_socio, 
                            "full_name" => $user->getUserById($player)->user->full_name]; 
              }
           }
       
       return $array;
    }

    public function advanceFilter(Request $request){
        return $this->service->advanceFilter($request);
    }

}