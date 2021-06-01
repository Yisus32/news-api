<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Hole;


use App\Core\CrudService;
use App\Repositories\Hole\HoleRepository;

/** @property HoleRepository $repository */
class HoleService extends CrudService
{

    protected $name = "hole";
    protected $namePlural = "holes";

    public function __construct(HoleRepository $repository)
    {
        parent::__construct($repository);
    }

}