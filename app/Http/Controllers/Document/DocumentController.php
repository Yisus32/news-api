<?php

namespace App\Http\Controllers\Document;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Document\DocumentService;
/** @property DocumentService $service */
class DocumentController extends CrudController
{
    public function __construct(DocumentService $service)
    {
        parent::__construct($service);

        $this->validateStore = [
            "guest_id" => "required",
            "name" => "required"
        ];

        $this->messages = [
            "required" => "El campo ' :attribute ' es requerido"
        ];
    }

    
}