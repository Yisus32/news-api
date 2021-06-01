<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Teetime_type;


use App\Core\CrudService;
use App\Repositories\Teetime_type\Teetime_typeRepository;

/** @property Teetime_typeRepository $repository */
class Teetime_typeService extends CrudService
{

    protected $name = "teetime_type";
    protected $namePlural = "teetime_types";

    public function __construct(Teetime_typeRepository $repository)
    {
        parent::__construct($repository);
    }

}