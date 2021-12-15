<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\waiting_list;


use App\Core\CrudService;
use App\Http\Mesh\UsuService;
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
        $date=$request->date;
        $hour=$request->start_hour;
        $fhour=$request->end_hour;
        $verifireser=Reservation::where('date',$date)->whereTime('start_hour', '>=',$hour)
        ->whereTime('end_hour', '<=', $fhour)->get();
        //dd($verifireser);
        if(count($verifireser)>0)
        {
            $request['sta']="A";
            return parent::_store($request);
        }

        else
        { 
            return response()->json(["error"=>true,"message"=> " No existen reservaciones para esta fecha y hora puede jugar"],422);
            
        }

    }

}