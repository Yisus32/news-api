<?php

namespace App\Http\Controllers\Bank;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Bank\BankService;
/** @property BankService $service */
class BankController extends CrudController
{
    public function __construct(BankService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
        	'country' => 'required',
        	'name' => 'required',
        	'coin_id' => 'required'

        ];

        $this->messages = [
            'country.required' => 'El nombre del país es requerido.',
            'name.required' => 'El nombre del banco es requerido.',
            'coin_id.required' => 'Seleccione una moneda'

        ];
    }
}