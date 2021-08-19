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
        $this->validateStore = [
            'id_toalla' => 'required',
            'user_id' => 'required'

            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function usotoalla(Request $request)
    {
        $r=$request->get('num');
      
       $bus=toalla::whereRaw("lower(num) like lower('%{$r}%')")->where('status','En uso')->get();
       return $bus;
    }


    public function stocktoalla(Request $request)
    {
        $r=$request->get('num');
      
       $bus=toalla::whereRaw("lower(num) like lower('%{$r}%')")->where('status','En stock')->get();
       return $bus;
    }

    
}