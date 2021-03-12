<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:33 PM
 */

namespace App\Http\Services;

use App\Core\CrudService;
use App\Http\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderService extends CrudService
{

    protected $name = "order";
    protected $namePlural = "orders";

    public function __construct(OrderRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        
    }

}