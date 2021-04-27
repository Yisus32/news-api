<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Bank_account;


use App\Core\CrudService;
use App\Repositories\Bank_account\Bank_accountRepository;

/** @property Bank_accountRepository $repository */
class Bank_accountService extends CrudService
{

    protected $name = "bank_account";
    protected $namePlural = "bank_accounts";

    public function __construct(Bank_accountRepository $repository)
    {
        parent::__construct($repository);
    }

}