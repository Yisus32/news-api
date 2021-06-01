<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:35 PM
 */

namespace App\Repositories\Examples;

use App\Core\TatucoRepository;
use App\Models\Example;
/** @property Example $model */
class ExampleRepository extends TatucoRepository
{

    public function __construct(Example $model)
    {
        parent::__construct($model);
    }

}