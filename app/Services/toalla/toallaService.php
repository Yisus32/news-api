<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\toalla;


use App\Core\CrudService;
use App\Repositories\toalla\toallaRepository;

/** @property toallaRepository $repository */
class toallaService extends CrudService
{

    protected $name = "toalla";
    protected $namePlural = "toallas";

    public function __construct(toallaRepository $repository)
    {
        parent::__construct($repository);
    }

}