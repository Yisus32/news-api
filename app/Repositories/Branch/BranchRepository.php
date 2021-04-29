<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Branch;

use App\Core\CrudRepository;
use App\Core\ImageService;
use App\Models\Branch;
use Illuminate\Http\Request;

/** @property Branch $model */
class BranchRepository extends CrudRepository
{
    public $data = [];
    public function __construct(Branch $model)
    {
        parent::__construct($model);
    }

    public function _store(Request $request)
    {
        
        if (isset($request->image) AND !empty($request->image)){
            $request->merge(['image' => ($this->saveImageFile($request->image, 'image'))]);
        }

        
        $branch =  $this->model::query()->create($request->all());
            
        $schedules = $request->input('schedules') ?? [];
        
        $branch->schedules()->createMany($schedules);

        return $branch;

    }


    private function saveImageFile($image, $imgType){
        $imageService = new ImageService();
        return filter_var($image, FILTER_VALIDATE_URL) ? $image :  $imageService->image($image);
    }

}