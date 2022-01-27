<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Type;


use App\Core\CrudService;
use App\Repositories\Type\TypeRepository;

/** @property TypeRepository $repository */
class TypeService extends CrudService
{

    protected $name = "type";
    protected $namePlural = "types";

    public function __construct(TypeRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _show($id, $request=null){
        return $this->repository->_show($id);
    }

}