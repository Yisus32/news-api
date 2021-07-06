<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\waiting_list;

use App\Core\CrudRepository;
use App\Models\waiting_list;

/** @property waiting_list $model */
class waiting_listRepository extends CrudRepository
{

    public function __construct(waiting_list $model)
    {
        parent::__construct($model);
    }

}