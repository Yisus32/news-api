<?php

namespace App\Http\Controllers\toalla;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\toalla;
use App\Services\toalla\toallaService;
/** @property toallaService $service */
class toallaController extends CrudController
{
    public function __construct(toallaService $service)
    {
        parent::__construct($service);
        $this->validateStore = [
            'num' => 'required',
            'description' => 'required',

            ];
    
        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    public function psearch(Request $request)
    {
        $r=$request->get('num');
      
       $bus=toalla::whereRaw('num like ?',"%{$r}%")->get();
       return response()->json($bus);
    }
}