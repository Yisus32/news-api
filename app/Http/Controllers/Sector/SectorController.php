<?php

namespace App\Http\Controllers\Sector;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Sector\SectorService;
/** @property SectorService $service */
class SectorController extends CrudController
{
    public function __construct(SectorService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            'country' => 'required'
        ];

        $this->messages = [
            'country.required' => 'El país es requerido.'

        ];
    }
}