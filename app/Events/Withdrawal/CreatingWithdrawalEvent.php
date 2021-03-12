<?php


namespace App\Events\Withdrawal;


use App\Events\Event;
use App\Exceptions\DomainExceptions\InsufficientFundsException;
use App\Models\Pocket;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CreatingWithdrawalEvent extends Event
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

        if (!$transaction->pocket_id) {
            /** @var Pocket $pocket */
            $pocket = Pocket::query()
                ->where('user_id', $this->request->get('user_id'))
                ->where('code', '=', Pocket::DEFAULT_CODE)
                ->first();

            if (empty($pocket)) {
                throw new \DomainException('The wallet need default pocket');
            }

            $transaction->pocket_id = $pocket->id;

            $wallet = $pocket->wallet;

            $transaction->wallet_id = $wallet->id;

            if (floatval($pocket->current_amount ) < floatval($transaction->amount ) ||
                floatval($wallet->total_amount ) < floatval($wallet->amount )
            ){
                throw new InsufficientFundsException("Fondos insuficientes");
            }

            //$pocket->current_amount = floatval($pocket->current_amount ) - floatval($transaction->amount );
            //$pocket->update();

            //$pocket->current_amount = floatval($pocket->current_amount ) - floatval($transaction->amount );
            //$pocket->update();

        }

        $transaction->type_id = 2;
        $transaction->status_id = 1;
    }
}