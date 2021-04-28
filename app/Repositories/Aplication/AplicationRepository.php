<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Aplication;

use App\Core\CrudRepository;
use App\Models\Aplication;

/** @property Aplication $model */
class AplicationRepository extends CrudRepository
{

    public function __construct(Aplication $model)
    {
        parent::__construct($model);
    }

}