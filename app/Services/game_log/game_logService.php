<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\game_log;


use App\Core\CrudService;
use App\Repositories\game_log\game_logRepository;
use Illuminate\Http\Request;
use App\Models\game_log;
use Carbon\Carbon;

/** @property game_logRepository $repository */
class game_logService extends CrudService
{

    protected $name = "game_log";
    protected $namePlural = "game_logs";

    public function __construct(game_logRepository $repository)
    {
        parent::__construct($repository);
    }
     

    public function _show($id, $request = null)
    {
        $id=Carbon::createFromFormat('d/m/Y',$id);
        $fill=game_log::where('fecha',$id);
        $id=$fill;
        return parent::_show($id);
    }
    

}