<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Schedule;


use App\Core\CrudService;
use App\Repositories\Schedule\ScheduleRepository;

/** @property ScheduleRepository $repository */
class ScheduleService extends CrudService
{

    protected $name = "schedule";
    protected $namePlural = "schedules";

    public function __construct(ScheduleRepository $repository)
    {
        parent::__construct($repository);
    }

}