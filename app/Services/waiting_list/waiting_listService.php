<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\waiting_list;


use App\Core\CrudService;
use App\Models\Reservation;
use App\Models\Teetime;
use App\Models\waiting_list;
use App\Repositories\waiting_list\waiting_listRepository;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpParser\Node\Stmt\Else_;

/** @property waiting_listRepository $repository */
class waiting_listService extends CrudService
{

    protected $name = "waiting_list";
    protected $namePlural = "waiting_lists";

    public function __construct(waiting_listRepository $repository)
    {
        parent::__construct($repository);
    }

    public function _store(Request $request)
    {
        
    }

}