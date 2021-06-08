<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Break_time;

use App\Core\CrudRepository;
use App\Models\Break_time;

/** @property Break_time $model */
class Break_timeRepository extends CrudRepository
{

    public function __construct(Break_time $model)
    {
        parent::__construct($model);
    }

}