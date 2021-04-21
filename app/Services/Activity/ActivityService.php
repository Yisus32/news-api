<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Activity;


use App\Core\CrudService;
use App\Repositories\Activity\ActivityRepository;

/** @property ActivityRepository $repository */
class ActivityService extends CrudService
{

    protected $name = "activity";
    protected $namePlural = "activities";

    public function __construct(ActivityRepository $repository)
    {
        parent::__construct($repository);
    }

}