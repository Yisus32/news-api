<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\asig_toalla;


use App\Core\CrudService;
use App\Repositories\asig_toalla\asig_toallaRepository;

/** @property asig_toallaRepository $repository */
class asig_toallaService extends CrudService
{

    protected $name = "asig_toalla";
    protected $namePlural = "asig_toallas";

    public function __construct(asig_toallaRepository $repository)
    {
        parent::__construct($repository);
    }

}