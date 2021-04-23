<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Sector;


use App\Core\CrudService;
use App\Repositories\Sector\SectorRepository;

/** @property SectorRepository $repository */
class SectorService extends CrudService
{

    protected $name = "sector";
    protected $namePlural = "sectors";

    public function __construct(SectorRepository $repository)
    {
        parent::__construct($repository);
    }

}