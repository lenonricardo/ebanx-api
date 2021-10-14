<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Exceptions\AccountException;


class AccountController extends Controller
{
    private $accounts;

    public function reset(Request $request) {
        $request->session()->flush();

        return response()->json([
            'message' => 'Success'
        ]);
    }

    public function balance(Request $request)
    {
        $amount = $request->session()->get('account_'.$request->input('account_id'));

        if (!$amount) {
            throw new AccountException();
        }

        return response()->json($amount);
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
        }

        return response()->json($response, 201);
    }
}
