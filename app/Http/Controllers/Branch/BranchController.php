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

        $this->validateStore = [
            'client_id' => 'required',
            'code' => 'required',
            'name' => 'required'
        ];

        $this->messages = [
            'client_id.required' => 'El id del cliente es requerido.',
            'code.required' => 'El codigo es requerido',
            'name.required' => 'El nombre de sucursal es requerido'

        ];
    }

    public function _store(Request $request)
    {
        return $this->service->_store($request);
    }
}