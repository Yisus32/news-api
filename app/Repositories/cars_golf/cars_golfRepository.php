<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\cars_golf;

use App\Core\CrudRepository;
use App\Models\cars_golf;

/** @property cars_golf $model */
class cars_golfRepository extends CrudRepository
{

    public function __construct(cars_golf $model)
    {
        parent::__construct($model);
    }

}