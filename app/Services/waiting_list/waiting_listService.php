<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\waiting_list;


use App\Core\CrudService;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Models\waiting_list;
use App\Repositories\waiting_list\waiting_listRepository;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Else_;

/** @property waiting_listRepository $repository */
class waiting_listService extends CrudService
{

    protected $name = "waiting_list";
    protected $namePlural = "waiting_lists";

    public function __construct(waiting_listRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
       
        $start_day=$request->date;
        
        //$start_day = Carbon::now(env('APP_TIMEZONE'))->format('Y-m-d');
        $shour=$request->start_hour;
        $ehour=$request->end_hour;
        $end_day = Teetime::max("end_date");
        if (isset($start_day) and isset($end_day)) {
            $teetimes = Teetime::whereBetween('start_date', array($start_day, $end_day))
                                ->OrwhereBetween('end_date', array($start_day, $end_day))
                                ->Orwhere('start_date', '<', "$start_day")
                                ->where('end_date', '>', "$start_day")
                                ->Orwhere('start_date', '<', "$end_day")
                                ->where('end_date', '>', "$end_day")
                                ->orderBy('start_date')
                                ->first();

          $resv=Reservation::where('teetime_id',$teetimes->id)
          ->where('date',$request->date)
          ->where('start_hour',$shour)->count();
          if($resv==0)
          {
            return Response()->json(["message"=>"no existe una reservacion pora esta fecha"], 200);
          }
          else
          {
              return parent::_store($request);
          }
           
        }

        else 
        {
            return Response()->json(["error"=>true, "message"=>"no existen registros de teetime"], 404);
        }
    }

}