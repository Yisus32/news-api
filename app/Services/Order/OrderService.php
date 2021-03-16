<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Order;


use App\Core\CrudService;
use App\Repositories\Order\OrderRepository;

/** @property OrderRepository $repository */
class OrderService extends CrudService
{

    protected $name = "order";
    protected $namePlural = "orders";

    public function __construct(OrderRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getByUser($user_id){
        return $this->repository->getByUser($user_id);
    }

}