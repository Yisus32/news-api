<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Sub_Activity;


use App\Core\CrudService;
use App\Repositories\Sub_Activity\Sub_ActivityRepository;

/** @property Sub_ActivityRepository $repository */
class Sub_ActivityService extends CrudService
{

    protected $name = "sub_activity";
    protected $namePlural = "sub_activities";

    public function __construct(Sub_ActivityRepository $repository)
    {
        parent::__construct($repository);
    }

}