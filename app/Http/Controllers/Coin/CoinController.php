<?php

namespace App\Http\Controllers\Coin;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Models\Coin;
use App\Services\Coin\CoinService;
use Illuminate\Validation\Rule;
/** @property CoinService $service */
class CoinController extends CrudController
{                                             
    public function __construct(CoinService $service)
    {
        parent::__construct($service);
    }

    public function ruler($name, $sym, $id = null){

        $coin = Coin::where('name','ILIKE', $name)
                    ->orWhere('symbol','ILIKE', $sym)
                    ->get();           

        if(!$coin->isEmpty()) {
            return true;
        }else{
            return false;
        }
    }

    public function _store(Request $request){

        $this->validate($request,[
            'name' => ['required', Rule::unique('coins')],
            'symbol' => ['required', Rule::unique('coins')]
        ]);
         if($this->ruler($request->name, $request->symbol)){
            return abort(422,'El nombre de la moneda o símbolo ya existe');    
         }else{
            return $this->service->_store($request);
         }
        
    }
    
    public function _update($id, Request $request){
        
         $ruler = Coin::where('name','ILIKE',$request->name)
                        ->orWhere('symbol','ILIKE',$request->symbol)
                        ->first();
    
        if($this->ruler($request->name, $request->symbol) && $id != $ruler->id){
             return abort(422, 'El nombre de la moneda símbolo ya existe');  
        }else{
            return $this->service->_update($id, $request);
        }
    }
}