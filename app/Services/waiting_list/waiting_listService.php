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
        $data=json_decode($request);


        $teetime_exist=Teetime::find($data[3]);

        $fec=DB::table('teetimes')->select('start_date','end_date')->where('id',$data[3])->get();
        
        foreach ($fec as $i)
        {
            $inter=waiting_list::whereBetween('date',[$fec['start_date'],$fec[1]])->get();
            return response()->json($inter);
        }
           
        
     
        
        
       
        return parent::_store($request);
    }

}