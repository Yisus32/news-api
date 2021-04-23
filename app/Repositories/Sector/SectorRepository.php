<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Sector;

use App\Core\CrudRepository;
use App\Models\Sector;

/** @property Sector $model */
class SectorRepository extends CrudRepository
{

    public function __construct(Sector $model)
    {
        parent::__construct($model);
    }

}