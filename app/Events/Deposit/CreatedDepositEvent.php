<?php


namespace App\Events\Deposit;


use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CreatedDepositEvent
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
        $details = $transaction->details()->newModelInstance();
        $attributes= $this->request->input('detail');

        $details->transaction_id         = $transaction->id;
        $details->method_payment         = $attributes['method_payment'];
        $details->method_payment_id      = $attributes['method_payment_id'];
        $details->method_payment_details = $attributes['method_detail'];
        $details->message                = $attributes['message'];

        $details->save();

    }
}