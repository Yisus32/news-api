<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\News;

use App\Core\CrudRepository;
use Illuminate\Http\Request;
use App\Models\News;
use App\Core\ImageService;

/** @property News $model */
class NewsRepository extends CrudRepository
{

    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    public function _index($data = null, $user = null){
         $news = News::where('account',$data['account'])->get();
          
          foreach ($news as $new) {
               $new['photo'] = json_decode($new['photo']);
          }

          return response()->json(["list" => $news, "count" => $news->count()]);
    }

    public function _store(Request $data){
        $data['code'] = substr(strtoupper(md5(rand())),1,5);

     // 1. Procesamos las imagenes de entrada.
       if(isset($data['photo']) && $data['photo'] != null){
          $img[] = (new ImageService())->image($data['photo']);
       }

       // 2. Reescribo el json con el nuevo valor de la imagen ahora codificado.
       if(isset($img)){
           $data['photo'] = json_encode($img);
       }
        
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