<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Guest\GuestService;
/** @property GuestService $service */
class GuestController extends CrudController
{
    public function __construct(GuestService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            "full_name" => "required",
            "identifier" => "required",
            "email" => "required|email",
        ];

        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido",
            "email" => "El campo ' :attribute ' debe ser un email válido"
        ];
    }

    public function _store(Request $request)
    {
        if (!isset($request->status)) {
            $request->status = 'No confirmado';
            $request["status"] = 'No confirmado';
        }

        return parent::_store($request);
    }

}