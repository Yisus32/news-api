<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Hole;

use App\Core\CrudRepository;
use App\Models\Hole;

/** @property Hole $model */
class HoleRepository extends CrudRepository
{

    public function __construct(Hole $model)
    {
        parent::__construct($model);
    }

}