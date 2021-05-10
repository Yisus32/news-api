<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Sector;

use App\Core\CrudRepository;
use App\Models\Sector;
use Illuminate\Support\Facades\DB;

/** @property Sector $model */
class SectorRepository extends CrudRepository
{

    public function __construct(Sector $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        if ($request->sector) {
            
            $sector = Sector::whereRaw("lower(sector) like lower('%{$request->sector}%')")->get();
            
            return $sector;
        }

        return parent::_index($request);
    }

}