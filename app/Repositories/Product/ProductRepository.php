<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Product;

use App\Core\CrudRepository;
use App\Models\Product;

/** @property Product $model */
class ProductRepository extends CrudRepository
{

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function _store($data)
    {
        return parent::_store($data);
    }

}