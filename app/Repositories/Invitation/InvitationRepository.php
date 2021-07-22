<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Invitation;

use App\Core\CrudRepository;
use App\Models\Invitation;

/** @property Invitation $model */
class InvitationRepository extends CrudRepository
{

    public function __construct(Invitation $model)
    {
        parent::__construct($model);
    }

}