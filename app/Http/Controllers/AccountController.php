<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Exceptions\AccountException;
use Illuminate\Support\Facades\Cache;

class AccountController extends Controller
{
    public function reset(Request $request) {
        Cache::flush();

        return 'OK';
    }

    public function balance(Request $request)
    {
        $account = new Account();
        $data = $account->getAccountData($request->input('account_id'));

        if (is_null($data)) {
            throw new AccountException();
        }

        return response()->json(intVal($data));
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
