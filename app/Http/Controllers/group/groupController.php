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

    public function psearch($id)
    {
        $bus=group::whereRaw('cod like ?',"%{$id}%")->get();
        return response()->json($bus);
    }
}