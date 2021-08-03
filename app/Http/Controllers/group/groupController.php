<?php

namespace App\Http\Controllers\group;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\group;
use App\Services\group\groupService;
/** @property groupService $service */
class groupController extends CrudController
{
    public function __construct(groupService $service)
    {
        parent::__construct($service);
    }

    public function psearch(Request $request)
    {
        $r=$request->get('cod');
        $bus=group::whereRaw('cod like ?',"%{$r}%")->get();
        return response()->json($bus);
    }
}