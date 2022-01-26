<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\News;

use App\Core\CrudRepository;
use Illuminate\Http\Request;
use App\Models\News;

/** @property News $model */
class NewsRepository extends CrudRepository
{

    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function _index($data = null, $user = null){
         return parent::_index($data);
    }

    public function _store(Request $data){
        $data['code'] = substr(strtoupper(md5(rand())),1,5);
        
        return parent::_store($data);

    }

    public function _show($id){
         return parent::_show($id);
    }

    public function _update($id, $data){
         return parent::_update($id,$data);
    }

    public function _delete($id){
         return parent::_delete($id);
    }

}