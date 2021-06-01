<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Teetime_type;

use App\Core\CrudRepository;
use App\Models\Teetime_type;

/** @property Teetime_type $model */
class Teetime_typeRepository extends CrudRepository
{

    public function __construct(Teetime_type $model)
    {
        parent::__construct($model);
    }

}