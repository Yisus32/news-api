<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Branch;


use App\Core\CrudService;
use App\Models\Branch;
use App\Models\Client;
use App\Repositories\Branch\BranchRepository;
use Illuminate\Http\Request;

/** @property BranchRepository $repository */
class BranchService extends CrudService
{

    protected $name = "branch";
    protected $namePlural = "branches";

    public function __construct(BranchRepository $repository)
    {
        parent::__construct($repository);
    }

  
    public function _store(Request $request)
    {
        $client = Client::find($request->client_id);
        $code_exist = Branch::where('code', $request->code)->first();
        if (!$client) {
            return response()->json(["error"=>true,"message"=>"Cliente no encontrado"],404);
        }
        if ($code_exist and !empty($request->code)) {
            return response()->json(["error"=>true,"message"=>"Código ya registrado"],409);
        }
        return $this->repository->_store($request);
    }

    public function getBySector(Request $request ,$sector_id){
        try {
            return $this->repository->getBySector($sector_id);
        } catch (\Throwable $th) {
            abort(400, "error al buscar las sucursales");
        }
    }

}