<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Status;

use App\Core\CrudRepository;
use App\Models\Status;

/** @property Status $model */
class StatusRepository extends CrudRepository
{

    public function __construct(Status $model)
    {
        parent::__construct($model);
    }

}