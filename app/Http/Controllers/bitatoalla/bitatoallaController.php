<?php

namespace App\Http\Controllers\bitatoalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\bitatoalla;
use App\Services\bitatoalla\bitatoallaService;
/** @property bitatoallaService $service */
class bitatoallaController extends CrudController
{
    public function __construct(bitatoallaService $service)
    {
        parent::__construct($service);
    }

    public function bita(Request $request)
    {
        $r=$request->get('id');
        $bus=bitatoalla::where('id_toalla',$r)->get();
        return  ["list"=>$bus,'total'=>count($bus)];
    }
}