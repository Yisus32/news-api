<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Sub_Activity;

use App\Core\CrudRepository;
use App\Models\Sub_Activity;

/** @property Sub_Activity $model */
class Sub_ActivityRepository extends CrudRepository
{

    public function __construct(Sub_Activity $model)
    {
        parent::__construct($model);
    }

}