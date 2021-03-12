<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:33 PM
 */

namespace App\Http\Services;

use App\Core\CrudService;
use App\Http\Repositories\StatusRepository;

class StatusService extends CrudService
{

    protected $name = "status";
    protected $namePlural = "statuses";

    public function __construct(StatusRepository $repository)
    {
        parent::__construct($repository);
    }

}