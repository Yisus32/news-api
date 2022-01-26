<?php

namespace App\Http\Controllers\News;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\News\NewsService;
/** @property NewsService $service */
class NewsController extends CrudController
{
    public function __construct(NewsService $service)
    {
        parent::__construct($service);
    }
}