<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Teetime;

use App\Core\CrudRepository;
use App\Models\Teetime;
use Illuminate\Http\Request;

/** @property Teetime $model */
class TeetimeRepository extends CrudRepository
{

    public function __construct(Teetime $model)
    {
        parent::__construct($model);
    }

    public function _store(Request $data)
    {
        if (isset($data["target"])){
            $data["target"] = $this->model->formatTypeArray($data["target"]);
        }
        if (isset($data["days"])){
            $data["days"] = $this->model->formatTypeArray($data["days"]);
        }
        $teetime = parent::_store($data);

        $break_times = $data['break_times'] ?? [];

        $teetime->break_times()->createMany($break_times);

        $teetime->break_times = $break_times;
        
        return  $teetime;
    }

    public function _update($id, $data)
    {
        if (isset($data["target"])){
            $data["target"] = $this->model->formatTypeArray($data["target"]);
        }
        if (isset($data["days"])){
            $data["days"] = $this->model->formatTypeArray($data["days"]);
        }
        
        $teetime = parent::_update($id, $data);

        if ($id and isset($data['break_times'])) {
            foreach ($data['break_times'] as $break) {
                isset($break['id'])
                    ? $teetime->break_times()->where('id',$break['id'])->update($break)
                    : $teetime->break_times()->create($break);
            }
        }

        return $teetime;
    }

}