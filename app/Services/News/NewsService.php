<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\News;


use App\Core\CrudService;
use App\Repositories\News\NewsRepository;
use Illuminate\Http\Request;

/** @property NewsRepository $repository */
class NewsService extends CrudService
{

    protected $name = "news";
    protected $namePlural = "news";

    public function __construct(NewsRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _index(Request $data){
         return $this->repository->_index($data);
    }

}