<?php

namespace App\Http\Controllers\asig_toalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\asig_toalla;
use App\Services\asig_toalla\asig_toallaService;
use App\Models\toalla;
use DateTime;
/** @property asig_toallaService $service */
class asig_toallaController extends CrudController
{
    public function __construct(asig_toallaService $service)
    {
        parent::__construct($service);
    }

    public function upsta(Request $request , $id)
    {
        $cam=toalla::where('id',$id)->first();
        $cam->status=$request->sta;
        $cam->save();
        $fec=new DateTime('now');
        $cfe=asig_toalla::where();

    }
}