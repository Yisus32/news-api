<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Break_time;


use App\Core\CrudService;
use App\Repositories\Break_time\Break_timeRepository;

/** @property Break_timeRepository $repository */
class Break_timeService extends CrudService
{

    protected $name = "break_time";
    protected $namePlural = "break_times";

    public function __construct(Break_timeRepository $repository)
    {
        parent::__construct($repository);
    }

}