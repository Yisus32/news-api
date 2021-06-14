<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Guest;


use App\Core\CrudService;
use App\Repositories\Guest\GuestRepository;
use Illuminate\Http\Request;

/** @property GuestRepository $repository */
class GuestService extends CrudService
{

    protected $name = "guest";
    protected $namePlural = "guests";

    public function __construct(GuestRepository $repository)
    {
        parent::__construct($repository);
    }

}