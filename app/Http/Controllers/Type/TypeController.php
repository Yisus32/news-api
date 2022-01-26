<?php

namespace App\Http\Controllers\Type;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Type\TypeService;
/** @property TypeService $service */
class TypeController extends CrudController
{
    public function __construct(TypeService $service)
    {
        parent::__construct($service);
    }
}