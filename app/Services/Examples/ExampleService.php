<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 * Date: 23/07/18
 * Time: 04:33 PM
 */

namespace App\Services\Examples;


use App\Core\TatucoService;
use App\Repositories\Examples\ExampleRepository;
/** @property ExampleRepository $repository */
class ExampleService extends TatucoService
{

    protected $name = "example";
    protected $namePlural = "examples";

    public function __construct(ExampleRepository $repository)
    {
        parent::__construct($repository);
    }


}