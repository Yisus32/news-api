<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\TempData;


use App\Core\CrudService;
use App\Repositories\TempData\TempDataRepository;

/** @property TempDataRepository $repository */
class TempDataService extends CrudService
{

    protected $name = "tempdata";
    protected $namePlural = "tempdatas";

    public function __construct(TempDataRepository $repository)
    {
        parent::__construct($repository);
    }

}