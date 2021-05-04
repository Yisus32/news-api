<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Client;

use App\Core\CrudRepository;
use App\Core\ImageService;
use App\Models\Client;
use Illuminate\Http\Request;

/** @property Client $model */
class ClientRepository extends CrudRepository
{

    public function __construct(Client $model)
    {
        parent::__construct($model);

        
    }

    public function _store($data)
    {
        if (isset($data->image) AND !empty($data->image)){
            $data->merge(['image' => ($this->saveImageFile($data->image, 'image'))]);
        }
        if (isset($data->logo) AND !empty($data->logo)){
            $data->merge(['logo' => ($this->saveImageFile($data->logo, 'logo'))]);
        }

        $client =  $this->model::query()->create($data->all());
       
        return $client;
    }

    public function searchByRif(Request $request){
        $client = Client::where('rif', $request->rif)->first();

        if (!$client) {
            return response()->json([
                'status' => 404,
                'message' => 'Cliente no existe'
            ], 404);
        }
        return $client;
    }


    private function saveImageFile($image, $imgType){
        $imageService = new ImageService();
        return filter_var($image, FILTER_VALIDATE_URL) ? $image :  $imageService->image($image);
    }
}