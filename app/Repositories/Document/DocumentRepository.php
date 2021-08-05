<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Document;

use App\Core\CrudRepository;
use App\Core\ImageService;
use App\Models\Document;
use Illuminate\Http\Request;

/** @property Document $model */
class DocumentRepository extends CrudRepository
{

    public function __construct(Document $model)
    {
        parent::__construct($model);
    }

    public function _store(Request $data){
        if (isset($data["document"])) {
            $data["document"] = (new ImageService)->document($data["document"]);
        }
        if (isset($data["front_image"])) {
            $data["front_image"] = (new ImageService)->image($data["front_image"]);
        }
        if (isset($data["back_image"])) {
            $data["back_image"] = (new ImageService)->image($data["back_image"]);
        }
        return parent::_store($data);
    }

    public function _update($id, $data){
        if (isset($data["document"])) {
            $data["document"] = (new ImageService)->document($data["document"]);
        }
        if (isset($data["front_image"])) {
            $data["front_image"] = (new ImageService)->image($data["front_image"]);
        }
        if (isset($data["back_image"])) {
            $data["back_image"] = (new ImageService)->image($data["back_image"]);
        }
        return parent::_update($id,$data);
    }
}