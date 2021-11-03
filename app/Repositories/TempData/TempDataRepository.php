<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\TempData;

use App\Core\CrudRepository;
use App\Models\TempData;

/** @property TempData $model */
class TempDataRepository extends CrudRepository
{

    public function __construct(TempData $model)
    {
        parent::__construct($model);
    }

}