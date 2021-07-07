<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\asig_toalla;

use App\Core\CrudRepository;
use App\Models\asig_toalla;
use App\Models\toalla;
use Carbon\Carbon;
use DateTime;

/** @property asig_toalla $model */
class asig_toallaRepository extends CrudRepository
{

    public function __construct(asig_toalla $model)
    {
        parent::__construct($model);
    }

   /* public function _show($id)
    {
        $fec=asig_toalla::where('id',$id)->get();
        $fi=$fec[0]->fec_ini;
        $ff=$fec[0]->fec_fin;
        $ff=new DateTime($ff);
        $fi=new DateTime($fi);
        $dif=date_diff($fi,$ff)->format('%R%a');
        $dif=intval($dif);
        dd($dif);
        return parent::_show($id);
    }*/

    public function _show($id)
    {
        $fec=asig_toalla::where('fec_fin',null)->get()->toarray();
        $now = Carbon::now();
        foreach ($fec as $i)
        {
            $d=new DateTime($fec[0]['fec_ini']);
            if($d->diffInHours($now)=='48')
            {
               dd('holis');
            }
            //dd($fec[0]['fec_ini']);
        }
    }

}