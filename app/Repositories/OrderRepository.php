<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:35 PM
 */

namespace App\Http\Repositories;

use App\Core\CrudRepository;
use App\Models\Order;

class OrderRepository extends CrudRepository
{

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

}