<?php


namespace App\Events\Wallet;


use App\Models\Pocket;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CreatedWalletEvent
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Wallet $wallet)
    {

        $pocket = $wallet->pockets()->newModelInstance();

        $pocket->name        = 'Monedero';
        $pocket->code        = Pocket::DEFAULT_CODE;
        $pocket->user_id     = $wallet->user_id;
        $pocket->wallet_id   = $wallet->id;
        $pocket->msa_account = $wallet->msa_account;

        $pocket->save();
    }
}