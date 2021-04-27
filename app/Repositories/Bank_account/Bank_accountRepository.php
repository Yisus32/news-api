<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Bank_account;

use App\Core\CrudRepository;
use App\Models\Bank_account;

/** @property Bank_account $model */
class Bank_accountRepository extends CrudRepository
{

    public function __construct(Bank_account $model)
    {
        parent::__construct($model);
    }

}