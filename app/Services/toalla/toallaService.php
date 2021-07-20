<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\toalla;


use App\Core\CrudService;
use App\Repositories\toalla\toallaRepository;
use Illuminate\Http\Request;
use App\Models\toalla;
use DateTime;
use App\Models\asig_toalla;
/** @property toallaRepository $repository */
class toallaService extends CrudService
{

    protected $name = "toalla";
    protected $namePlural = "toallas";

    public function __construct(toallaRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _update($id, Request $request)
    {
        
        $fec=new DateTime('now');
        $oasi=asig_toalla::where('id_toalla',$id)->orderby('created_at','DESC')->take(1)->get();
        $ida=$oasi[0]->id;

        $cfe=asig_toalla::where('id',$ida)->first();
        $cfe->fec_fin=$fec;
        $cfe->save();

        return parent::_update($id,$request);
    }

}