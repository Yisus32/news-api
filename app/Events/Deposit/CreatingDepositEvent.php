<?php


namespace App\Events\Deposit;


use App\Events\Event;
use App\Models\Pocket;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CreatingDepositEvent extends Event
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Transaction $transaction)
    {

        if (!$transaction->pocket_id){
            /** @var Pocket $pocket */
            $pocket = Pocket::query()
                ->where('user_id',$this->request->get('user_id'))
                ->where('code','=',Pocket::DEFAULT_CODE)
                ->first()
            ;
            if (empty($pocket)){
                throw new \DomainException('The wallet need default pocket');
            }
            $transaction->pocket_id = $pocket->id;
            $transaction->wallet_id = $pocket->wallet->id;

        }

        $transaction->type_id = 1;
        $transaction->status_id = 1;
    }
}