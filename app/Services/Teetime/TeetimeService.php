<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Teetime;


use App\Core\CrudService;
use App\Http\Mesh\AccountService;
use App\Models\Hole;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Repositories\Teetime\TeetimeRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/** @property TeetimeRepository $repository */
class TeetimeService extends CrudService
{

    protected $name = "teetime";
    protected $namePlural = "teetimes";

    public function __construct(TeetimeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        if (isset($request->time_interval)) {
            if (($request->time_interval % 5) != 0) {
                return response()->json(['error' => true, 'message' => 'El intervalo de tiempo debe ser multiplo de 5'],400);
            }
        }

        foreach ($request->target as $hole) {
            $hole_exist = Hole::find($hole);
            if (!$hole_exist) {
                return response()->json(['error' => true, 'message' => "El hoyo con id $hole no existe"],400);
            }

            //checar que no exista teetime encima de otro

            $teetime = Teetime::whereBetween('start_date', array($request->start_date, $request->end_date))
                                ->whereRaw("$hole = ANY(target)")
                                ->OrwhereBetween('end_date', array($request->start_date, $request->end_date))
                                ->whereRaw("$hole = ANY(target)")
                                ->get();

            if (count($teetime) > 0) {
                return response()->json(['error' => true, 'message' => "El hoyo $hole_exist->name esta apartado para otra programación"],400);
            }
            
        }

        $account = new AccountService();
        $account = $account->getAccount();
        if (!isset($account->time_zone)) {
            return response()->json(["error" => true, "message" => "Error en la zona horaria del sistema"], 400);
        }

        $request->created_at = $account->time_zone;
        $request["created_at"] = $account->time_zone;
        

        return parent::_store($request);
    }

    public function _update($id, Request $request)
    {
        if (isset($request->time_interval)) {
            if (($request->time_interval % 5) != 0) {
                return response()->json(['error' => true, 'message' => 'El intervalo de tiempo debe ser multiplo de 5'],400);
            }
        }
        $reservation_exist = Reservation::where('teetime_id', '=', "$id")->where("status", "=", "registrado")->first();
        if ($reservation_exist) {
            return response()->json(['error' => true, "message" => 'Existen reservaciones asociadas a la programación'],409);
        }
        
        return parent::_update($id, $request);
    }

    public function _delete($id)
    {   
        $reservation_exist = Reservation::where('teetime_id', '=', "$id")->where("status", "=", "registrado")->first();
        if ($reservation_exist) {
            return response()->json(['error' => true, "message" => 'Existen reservaciones asociadas a la programación'],409);
        }

        $teetime = Teetime::find($id);
        $teetime->reservations()->delete();
        $teetime->break_times()->delete();

        return parent::_delete($id);
    }
    
    public function available(Request $request){

        

        try{

            $teetime = $this->repository->available($request);

            if(!$teetime)
            {
                return response()->json([
                    "status" => 404,
                    'message'=>$this->name. ' no existe'
                ], 404);
            }

            Log::info('Encontrado');

            return response()->json([$teetime], 200);

        }catch (\Exception $e){
            return $this->errorException($e);
        }

        
    }

}