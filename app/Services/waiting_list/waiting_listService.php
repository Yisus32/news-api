<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\waiting_list;


use App\Core\CrudService;
use App\Models\Teetime;
use App\Models\waiting_list;
use App\Repositories\waiting_list\waiting_listRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
        $date=$request->all();
        foreach($date as $e)
        {
            $r=$e[0];
            $w=settype($r,'integer');
            $fec=DB::table('teetimes')->select('start_date','end_date')->where('id',$w)->get()->toArray();

        //de aqui ya no funciona
            $inter=waiting_list::whereBetween('date',[$fec[0]->start_date, $fec[0]->end_date])->get();
            if($inter->count()==0)
            {
                return response()->json(['error' => true, 'message' => "No existe un teetime en el intervalo de fechas"],400);
            }
        
        }
    
            
        
        
       
        return parent::_store($request);
    }

}