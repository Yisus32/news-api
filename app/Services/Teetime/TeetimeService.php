<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Teetime;


use App\Core\CrudService;
use App\Repositories\Teetime\TeetimeRepository;

/** @property TeetimeRepository $repository */
class TeetimeService extends CrudService
{

    protected $name = "teetime";
    protected $namePlural = "teetimes";

    public function __construct(TeetimeRepository $repository)
    {
        parent::__construct($repository);
    }

}