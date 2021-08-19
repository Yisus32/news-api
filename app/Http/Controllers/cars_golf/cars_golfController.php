<?php

namespace App\Http\Controllers\cars_golf;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\cars_golf\cars_golfService;
use App\Models\cars_golf;
/** @property cars_golfService $service */
class cars_golfController extends CrudController
{
    public function __construct(cars_golfService $service)
    {
        parent::__construct($service);
    }

    public function psearch(Request $request)
    {
        $r=$request->get('cod');
        $bus=cars_golf::whereRaw("lower(cod) like lower('%{$r}%')")->get();
        return response()->json($bus);
    }
}