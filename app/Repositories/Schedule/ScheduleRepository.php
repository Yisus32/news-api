<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Schedule;

use App\Core\CrudRepository;
use App\Models\Schedule;

/** @property Schedule $model */
class ScheduleRepository extends CrudRepository
{

    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

}