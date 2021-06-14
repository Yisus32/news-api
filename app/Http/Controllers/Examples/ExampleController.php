<?php

namespace App\Http\Controllers\Examples;

use App\Core\CrudController;
use App\Core\TatucoController;
use App\Services\Examples\ExampleService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** @property ExampleService $service */
class ExampleController extends CrudController
{

    public $validateStore = [
        "code" => ["required"],
        "name"=>"required"
    ];

    public $validateUpdate = [];
    public $messages = [
        "required" => "El campo ' :attribute ' es requerido",
        "unique" => "El código ya se encuentra en uso"
    ];


    public function __construct(ExampleService $service)
    {
        parent::__construct($service);
    }

    public function _store(Request $request)
    {
        $this->validateStore['code'][] = Rule::unique('examples');
        $request->merge(['code' => strtoupper($request->input('code'))]);
        return parent::_store($request);
    }


    public function _update($id,Request $request)
    {
        $this->validateUpdate = [
            "code"=> Rule::unique('example')->ignore($id)
        ];
        $request->merge(['code' => strtoupper($request->input('code'))]);
        return parent::_update($id,$request);
    }

}