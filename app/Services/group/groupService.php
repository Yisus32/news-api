<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\group;


use App\Core\CrudService;
use App\Repositories\group\groupRepository;

/** @property groupRepository $repository */
class groupService extends CrudService
{

    protected $name = "group";
    protected $namePlural = "groups";

    public function __construct(groupRepository $repository)
    {
        parent::__construct($repository);
    }

}