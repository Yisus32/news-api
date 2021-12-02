<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Filter;


use App\Core\CrudService;
use App\Repositories\Filter\FilterRepository;

/** @property FilterRepository $repository */
class FilterService extends CrudService
{

    protected $name = "filter";
    protected $namePlural = "filters";

    public function __construct(FilterRepository $repository)
    {
        parent::__construct($repository);
    }

}