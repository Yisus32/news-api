<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\number_holes;

use App\Core\CrudRepository;
use App\Models\number_holes;

/** @property number_holes $model */
class number_holesRepository extends CrudRepository
{

    public function __construct(number_holes $model)
    {
        parent::__construct($model);
    }

}