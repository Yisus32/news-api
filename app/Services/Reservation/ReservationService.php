<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Reservation;


use App\Core\CrudService;
use App\Http\Mesh\AccountService;
use App\Models\Guest;
use App\Models\Hole;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Repositories\Reservation\ReservationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//commit de reposicion

/** @property ReservationRepository $repository */
class ReservationService extends CrudService
{

    protected $name = "reservation";
    protected $namePlural = "reservations";

    public function __construct(ReservationRepository $repository)
    {
        parent::__construct($repository);
    }

   public function _store(Request $data){
        $check = $this->repository->checkCapacity($data['partners'],$data['guests'],$data['guests_email'],$data['teetime_id']);

        if (is_int($check)) {
        	return $this->repository->_store($data);
        }else {
        	return $check;
        }
   }

    public function apartReservation(Request $data){
        return $this->repository->apartReservation($data);                                  
    }

   public function _update($id,$data){

      $check = $this->repository->checkCapacity($data['partners'],$data['guests'],$data['guests_email'],$data['teetime_id']);

      if (is_int($check)) {
        return $this->repository->_update($id,$data);
      }else{
        return $check;
      }
      
   }

   public function cancelReservation($id){
   		return $this->repository->cancelReservation($id);
   }

   public function resendInvitation($reservation_id,Request $request){
      return $this->repository->resendInvitation($reservation_id,$request);
   }

   public function standByTeetime(Request $request,$id,$hole_id){
     return $this->repository->standByTeetime($request,$id,$hole_id);
   }

   public function restartTeetime(Request $request,$id,$hole_id){
        return $this->repository->restartTeetime($request,$id,$hole_id);
    }

    public function advanceFilter(Request $request){
      return $this->repository->advanceFilter($request);
    }
}