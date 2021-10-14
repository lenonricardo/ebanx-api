<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;


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
        $accountId = 'account_'.$request->input('account_id');
        return response()->json([
            'test' => $request->session()->get($accountId)
        ]);
    }

    public function event(Request $request)
    {
        $account = new Account();

        switch($request->input('type')) {
            case 'deposit':
                $account->makeDeposit($request);
                $response = [
                    'destination' => $account->getAccountInfo()
                ];
            break;
        }

        return response()->json($response);
    }
}
