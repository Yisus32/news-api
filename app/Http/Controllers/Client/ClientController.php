<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Client\ClientService;
/** @property ClientService $service */
class ClientController extends CrudController
{
    public function __construct(ClientService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'commerce_name' => 'required',
            'rif' => 'unique:clients'

        ];

        $this->messages = [
            'commerce_name.required' => 'El nombre comercial es requerido.',
            'rif.unique' => 'El rif ingresado ya se encuentra registrado'

        ];
    }

    public function _store(Request $request)
    {
        return parent::_store($request);
    }

    public function searchByRif(Request $request){
        return $this->service->searchByRif($request);
    }
}