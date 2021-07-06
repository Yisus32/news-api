<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\asig_toalla;


use App\Core\CrudService;
use App\Models\toalla;
use App\Repositories\asig_toalla\asig_toallaRepository;
use Illuminate\Http\Request;

/** @property asig_toallaRepository $repository */
class asig_toallaService extends CrudService
{

    protected $name = "asig_toalla";
    protected $namePlural = "asig_toallas";

    public function __construct(asig_toallaRepository $repository)
    {
        parent::__construct($repository);
    }
     
    public function _store(Request $request)
    {
        $date=$request->all();
        $toalla=toalla::where('id',$date['id_toalla'])->first();
        $toalla->fec=$date['fec_fin'];
        $toalla->save();
        
        
        return parent::_store($request);
    }
}