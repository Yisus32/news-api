<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\bitatoalla;

use App\Core\CrudRepository;
use App\Models\bitatoalla;

/** @property bitatoalla $model */
class bitatoallaRepository extends CrudRepository
{

    public function __construct(bitatoalla $model)
    {
        parent::__construct($model);
    }

}