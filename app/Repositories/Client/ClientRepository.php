<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Client;

use App\Core\CrudRepository;
use App\Core\ImageService;
use App\Models\Client;

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


    private function saveImageFile($image, $imgType){
        $imageService = new ImageService();
        return filter_var($image, FILTER_VALIDATE_URL) ? $image :  $imageService->image($image);
    }
}