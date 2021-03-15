<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Order;

use App\Core\CrudRepository;
use App\Models\Order;

/** @property Order $model */
class OrderRepository extends CrudRepository
{

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

}