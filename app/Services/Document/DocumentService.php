<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Document;


use App\Core\CrudService;
use App\Repositories\Document\DocumentRepository;
use Illuminate\Http\Request;

/** @property DocumentRepository $repository */
class DocumentService extends CrudService
{

    protected $name = "document";
    protected $namePlural = "documents";

    public function __construct(DocumentRepository $repository)
    {
        parent::__construct($repository);
    }

}