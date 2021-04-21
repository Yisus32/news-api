<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Activity;

use App\Core\CrudRepository;
use App\Models\Activity;

/** @property Activity $model */
class ActivityRepository extends CrudRepository
{

    public function __construct(Activity $model)
    {
        parent::__construct($model);
    }

}