<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Client;

use App\Core\CrudRepository;
use App\Models\Client;

/** @property Client $model */
class ClientRepository extends CrudRepository
{

    public function __construct(Client $model)
    {
        parent::__construct($model);
    }

}