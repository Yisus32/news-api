<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\asig_toalla;

use App\Core\CrudRepository;
use App\Models\asig_toalla;

/** @property asig_toalla $model */
class asig_toallaRepository extends CrudRepository
{

    public function __construct(asig_toalla $model)
    {
        parent::__construct($model);
    }

}