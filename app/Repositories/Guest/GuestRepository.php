<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Guest;

use App\Core\CrudRepository;
use App\Models\Guest;

/** @property Guest $model */
class GuestRepository extends CrudRepository
{

    public function __construct(Guest $model)
    {
        parent::__construct($model);
    }

}