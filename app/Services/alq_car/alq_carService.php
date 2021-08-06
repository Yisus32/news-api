<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\alq_car;


use App\Core\CrudService;
use App\Repositories\alq_car\alq_carRepository;

/** @property alq_carRepository $repository */
class alq_carService extends CrudService
{

    protected $name = "alq_car";
    protected $namePlural = "alq_cars";

    public function __construct(alq_carRepository $repository)
    {
        parent::__construct($repository);
    }

}