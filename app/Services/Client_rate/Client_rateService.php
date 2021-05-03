<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Client_rate;


use App\Core\CrudService;
use App\Repositories\Client_rate\Client_rateRepository;

/** @property Client_rateRepository $repository */
class Client_rateService extends CrudService
{

    protected $name = "client_rate";
    protected $namePlural = "client_rates";

    public function __construct(Client_rateRepository $repository)
    {
        parent::__construct($repository);
    }

}