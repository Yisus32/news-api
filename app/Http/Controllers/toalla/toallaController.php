<?php

namespace App\Http\Controllers\toalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\toalla\toallaService;
use App\Models\asig_toalla;
use App\Services\asig_toalla\asig_toallaService;
use App\Models\toalla;
use DateTime;
/** @property toallaService $service */
class toallaController extends CrudController
{
    public function __construct(toallaService $service)
    {
        parent::__construct($service);
    }

    public function upsta(Request $request , $id)
    {
        $cam=toalla::where('id',$id)->first();
        $cam->status=$request->sta;
        $cam->save();

        $fec=new DateTime('now');
        $ida=$request->id;

        $cfe=asig_toalla::where('id',$ida)->first();
        $cfe->fec_fin=$fec;
        $cfe->save();

    }
}