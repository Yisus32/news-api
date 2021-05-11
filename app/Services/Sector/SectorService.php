<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Sector;


use App\Core\CrudService;
use App\Repositories\Sector\SectorRepository;
use Illuminate\Http\Request;

/** @property SectorRepository $repository */
class SectorService extends CrudService
{

    protected $name = "sector";
    protected $namePlural = "sectors";

    public function __construct(SectorRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(Request $request)
    {
        if (isset($request->sector)) {
            try {
                return $this->repository->_index($request);
            } catch (\Throwable $th) {
                return $th;
            }
        }

        return parent::_index($request);
    }

}