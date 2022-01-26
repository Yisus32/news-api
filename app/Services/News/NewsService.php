<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\News;


use App\Core\CrudService;
use App\Repositories\News\NewsRepository;

/** @property NewsRepository $repository */
class NewsService extends CrudService
{

    protected $name = "news";
    protected $namePlural = "news";

    public function __construct(NewsRepository $repository)
    {
        parent::__construct($repository);
    }

}