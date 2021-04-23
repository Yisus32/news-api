<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Branch;

use App\Core\CrudRepository;
use App\Models\Branch;

/** @property Branch $model */
class BranchRepository extends CrudRepository
{

    public function __construct(Branch $model)
    {
        parent::__construct($model);
    }

}