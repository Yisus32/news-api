<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Bank;


use App\Core\CrudService;
use App\Repositories\Bank\BankRepository;

/** @property BankRepository $repository */
class BankService extends CrudService
{

    protected $name = "bank";
    protected $namePlural = "banks";

    public function __construct(BankRepository $repository)
    {
        parent::__construct($repository);
    }
    
}