<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Branch;


use App\Core\CrudService;
use App\Repositories\Branch\BranchRepository;
use Illuminate\Http\Request;

/** @property BranchRepository $repository */
class BranchService extends CrudService
{

    protected $name = "branch";
    protected $namePlural = "branches";

    public function __construct(BranchRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        return $this->repository->_store($request);
    }

}