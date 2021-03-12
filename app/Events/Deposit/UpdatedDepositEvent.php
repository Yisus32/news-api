<?php


namespace App\Events\Deposit;


use App\Models\Transaction;
use Illuminate\Http\Request;

class UpdatedDepositEvent
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
        if ($transaction->status == 2){
            $wallet = $transaction
                ->wallet()
                ->firstOrFail();

            $wallet->total_amount = floatval($wallet->total_amount ) + floatval($transaction->amount );

            $wallet->update();

        }

    }
}