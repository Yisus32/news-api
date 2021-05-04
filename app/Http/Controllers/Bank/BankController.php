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
    }
}