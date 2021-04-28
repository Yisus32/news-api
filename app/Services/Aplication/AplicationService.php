<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Aplication;


use App\Core\CrudService;
use App\Repositories\Aplication\AplicationRepository;

/** @property AplicationRepository $repository */
class AplicationService extends CrudService
{

    protected $name = "aplication";
    protected $namePlural = "aplications";

    public function __construct(AplicationRepository $repository)
    {
        parent::__construct($repository);
    }

}