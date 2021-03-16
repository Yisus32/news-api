<?php

namespace App\Http\Controllers\Status;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\Status;
use App\Services\Status\StatusService;
/** @property StatusService $service */
class StatusController extends CrudController
{
    public function __construct(StatusService $service)
    {
        parent::__construct($service);
    }

    public function delete($id){
        $status = Status::find($id);

        if (!$status) {
            return response()->json([
                'status' => 404,
                'message' => $this->name . ' no existe'
            ], 404);
        }

        $status->delete();

        return response()->json([
            'status' => 206,
            'message' => $this->name . ' Eliminado'
        ], 206);
    }
}