<?php

namespace App\Http\Controllers\Bank_account;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Bank_account\Bank_accountService;
/** @property Bank_accountService $service */
class Bank_accountController extends CrudController
{
    public function __construct(Bank_accountService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'client_id' => 'required',
            'bank_id' => 'required',
            'name' => 'required',
            'identifier' => 'required',
            'account_number' => 'required',
            'account_type' => 'required'
        ];

        $this->messages = [
            'client_id.required' => 'El id del cliente es requerido.',
            'bank_id.required' => 'El id del banco es requerido.',
            'name.required' => 'El nombre del banco es requerido.',
            'identifier.required' => 'El identificador del usuario del banco es requerido.',
            'account_number.required' => 'El numero de cuenta es requerido.',
            'account_type.required' => 'El tipo de cuenta es requerido.'

        ];
    }
}