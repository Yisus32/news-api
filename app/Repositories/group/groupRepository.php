<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\group;

use App\Core\CrudRepository;
use App\Models\group;

/** @property group $model */
class groupRepository extends CrudRepository
{

    public function __construct(group $model)
    {
        parent::__construct($model);
    }

}