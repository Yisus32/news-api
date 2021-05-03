<?php

namespace App\Http\Controllers\Coin;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Coin\CoinService;
/** @property CoinService $service */
class CoinController extends CrudController
{
    public function __construct(CoinService $service)
    {
        parent::__construct($service);
    }
}