<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Bank;

use App\Core\CrudRepository;
use App\Models\Bank;

/** @property Bank $model */
class BankRepository extends CrudRepository
{

    public function __construct(Bank $model)
    {
        parent::__construct($model);
    }

}