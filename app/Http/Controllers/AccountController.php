<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Exceptions\AccountException;
use Illuminate\Support\Facades\Cache;


class AccountController extends Controller
{
    private $accounts;

    public function reset(Request $request) {
        Cache::flush();

        return 'OK';
    }

    public function balance(Request $request)
    {
        $amount = Cache::get('account_'.$request->input('account_id'));

        if (!$amount) {
            throw new AccountException();
        }

        return response()->json(intVal($amount));
    }

    public function event(Request $request)
    {
        $account = new Account();

        switch($request->input('type')) {
            case 'deposit':
                $account->makeDeposit();
                $response = [
                    'destination' => $account->getAccountInfo()
                ];
                break;
            case 'withdraw':
                $account->makeWithdraw();
                $response = [
                    'origin' => $account->getAccountInfo()
                ];
                break;
            case 'transfer':
                $account->makeTransfer();
                $response = $account->getTransferInfo();
                break;
        }

        return response()->json($response, 201);
    }
}
