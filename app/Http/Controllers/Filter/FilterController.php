<?php

namespace App\Http\Controllers\Filter;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Filter\FilterService;
/** @property FilterService $service */
class FilterController extends CrudController
{
    public function __construct(FilterService $service)
    {
        parent::__construct($service);
    }
}