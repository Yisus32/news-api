<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:35 PM
 */

namespace App\Http\Repositories;

use App\Core\CrudRepository;
use App\Models\Product;

class ProductRepository extends CrudRepository
{

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

}