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
    }
}