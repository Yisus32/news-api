<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Teetime;

use App\Core\CrudRepository;
use App\Models\Teetime;

/** @property Teetime $model */
class TeetimeRepository extends CrudRepository
{

    public function __construct(Teetime $model)
    {
        parent::__construct($model);
    }

}