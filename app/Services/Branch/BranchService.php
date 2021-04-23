<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Branch;


use App\Core\CrudService;
use App\Repositories\Branch\BranchRepository;

/** @property BranchRepository $repository */
class BranchService extends CrudService
{

    protected $name = "branch";
    protected $namePlural = "branches";

    public function __construct(BranchRepository $repository)
    {
        parent::__construct($repository);
    }

}