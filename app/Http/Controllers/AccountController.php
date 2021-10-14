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
        return response()->json([
            'test' => $request->session()->get($request->input('account_id'))
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

        return response()->json([
            $request->session()
        ]);
    }
}
