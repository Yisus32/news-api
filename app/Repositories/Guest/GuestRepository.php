<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Repositories\Guest;

use App\Core\CrudRepository;
use App\Core\ImageService;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** @property Guest $model */
class GuestRepository extends CrudRepository
{

    public function __construct(Guest $model)
    {
        parent::__construct($model);
    }

    public function _index($request = null, $user = null)
    {
        if (isset($request->email)){
            return Guest::whereraw("lower(email) like lower('%{$request->email}%')")->get();
        }else{
            $guests = Guest::all();
        }
         
        foreach ($guests as $guest) {
            $guest->documents = $guest->documents()->get();
        }
        
        return $guests;
    }

    public function _show($id)
    {
        $guest = Guest::find($id);

        $guest->documents = $guest->documents()->get();

        return $guest;
        
    }

    public function _store(Request $data)
    {
        $documents = $data['documents'] ?? [];

        if (isset($data->documents)) {

            for ($i=0; $i < count($documents); $i++) { 
                if (isset($documents[$i]["document"])) {
                    $documents[$i]["document"] = (new ImageService)->document($documents[$i]["document"]);
                }
                if (isset($documents[$i]["front_image"])) {
                    $documents[$i]["front_image"] = (new ImageService)->image($documents[$i]["front_image"]);
                }
                if (isset($documents[$i]["back_image"])) {
                    $documents[$i]["back_image"] = (new ImageService)->image($documents[$i]["back_image"]);
                }
            }
            
        }

        $guest = parent::_store($data);

        $guest->documents()->createMany($documents);

        $guest->documents = $documents;

        return $guest;
    }

    public function _update($id, $data)
    {
        $guest = parent::_update($id, $data);

        $documents = $data['documents'] ?? [];

            
        if ($id and isset($data['documents'])) {
            foreach ($data['documents'] as $doc) {
                if (isset($doc["document"])) {
                    $doc["document"] = (new ImageService)->document($doc["document"]);
                }
                if (isset($doc["front_image"])) {
                    $doc["front_image"] = (new ImageService)->image($doc["front_image"]);
                }
                if (isset($doc["back_image"])) {
                    $doc["back_image"] = (new ImageService)->image($doc["back_image"]);
                }

                isset($doc['id'])
                    ? $guest->documents()->where('id',$doc['id'])->update($doc)
                    : $guest->documents()->create($doc);
            }
        }

        return $guest;
    }

}