<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\bitatoalla;


use App\Core\CrudService;
use App\Repositories\bitatoalla\bitatoallaRepository;

/** @property bitatoallaRepository $repository */
class bitatoallaService extends CrudService
{

    protected $name = "bitatoalla";
    protected $namePlural = "bitatoallas";

    public function __construct(bitatoallaRepository $repository)
    {
        parent::__construct($repository);
    }

}