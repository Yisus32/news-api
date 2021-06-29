<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\game_log;

use App\Core\CrudRepository;
use App\Models\game_log;

/** @property game_log $model */
class game_logRepository extends CrudRepository
{

    public function __construct(game_log $model)
    {
        parent::__construct($model);
    }


}