<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\toalla;

use App\Core\CrudRepository;
use App\Models\toalla;

/** @property toalla $model */
class toallaRepository extends CrudRepository
{

    public function __construct(toalla $model)
    {
        parent::__construct($model);
    }

}