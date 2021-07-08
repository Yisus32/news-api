<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\asig_toalla;

use App\Core\CrudRepository;
use App\Models\asig_toalla;
use App\Models\toalla;
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
            $now= new DateTime('now');
            $f=asig_toalla::where('fec_fin',null)->count();
            $x=0;
            foreach ($fec as $i)
            {
                while($x!=$f)
                {
                    $con=$fec[$x]['fec_ini'];
                    $id=$fec[$x]['id_toalla'];
                    $fed=new DateTime($con);
                    $dife=$now->diff($fed);
                    if($dife->h >= 1)
                    {
                        $cam=toalla::where('id',$id)->first();
                        $cam->status='perdida';
                        $cam->save();

                    }
                  
                    $x++;
                }
                    
            }
           return parent::_show($id);
            //dd($fec[0]['fec_ini']);
        
    }

}