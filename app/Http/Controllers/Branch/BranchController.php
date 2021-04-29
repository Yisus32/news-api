<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Branch\BranchService;
/** @property BranchService $service */
class BranchController extends CrudController
{
    public function __construct(BranchService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {
        return $this->service->_store($request);
    }
}