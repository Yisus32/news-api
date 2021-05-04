<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Client;


use App\Core\CrudService;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;

/** @property ClientRepository $repository */
class ClientService extends CrudService
{

    protected $name = "client";
    protected $namePlural = "clients";

    public function __construct(ClientRepository $repository)
    {
        parent::__construct($repository);
    }

    

    public function searchByRif(Request $request){
        return $this->repository->searchByRif($request);
    }

}