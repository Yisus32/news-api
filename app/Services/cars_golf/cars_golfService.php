<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\cars_golf;


use App\Core\CrudService;
use App\Repositories\cars_golf\cars_golfRepository;

/** @property cars_golfRepository $repository */
class cars_golfService extends CrudService
{

    protected $name = "cars_golf";
    protected $namePlural = "cars_golves";

    public function __construct(cars_golfRepository $repository)
    {
        parent::__construct($repository);
    }

}