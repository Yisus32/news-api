<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\number_holes;


use App\Core\CrudService;
use App\Repositories\number_holes\number_holesRepository;

/** @property number_holesRepository $repository */
class number_holesService extends CrudService
{

    protected $name = "number_holes";
    protected $namePlural = "number_holes";

    public function __construct(number_holesRepository $repository)
    {
        parent::__construct($repository);
    }

}