<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Client_rate;

use App\Core\CrudRepository;
use App\Models\Client_rate;

/** @property Client_rate $model */
class Client_rateRepository extends CrudRepository
{

    public function __construct(Client_rate $model)
    {
        parent::__construct($model);
    }

}