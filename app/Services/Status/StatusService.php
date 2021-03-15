<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Status;


use App\Core\CrudService;
use App\Repositories\Status\StatusRepository;

/** @property StatusRepository $repository */
class StatusService extends CrudService
{

    protected $name = "status";
    protected $namePlural = "statuses";

    public function __construct(StatusRepository $repository)
    {
        parent::__construct($repository);
    }

}